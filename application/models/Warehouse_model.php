<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Warehouse_model extends CI_Model
{
	public function getAllUsers()
	{
		return $this->db->get('user')->result_array();
	}

	public function insertData($table, $Data)
	{
		return $this->db->insert($table, $Data);
	}

	public function getMaterialDetails($id_material)
	{
		$query = $this->db->get_where('material_list', array('id_material' => $id_material));
		return $query->result_array();
	}

	public function getBox()
	{
		return $this->db->get('box')->result_array();
	}

	public function getReceivingMaterials()
	{
		return $this->db->get('receiving_material_temp')->result_array();
	}

	public function getListStorage()
	{
		return $this->db->query("SELECT
        ls.*,
        sub.total_qty
    FROM
        list_storage ls
    JOIN (
        SELECT
            product_id,
            SUM(total_qty) AS total_qty
        FROM
            list_storage
        GROUP BY
            product_id
    ) sub ON ls.product_id = sub.product_id
    ORDER BY
        ls.id DESC;
    ")->result_array();
	}

	public function getListBox()
	{
		return $this->db->query("SELECT a.*, b.SLoc as sloc_name from `box` a left join storage b on a.sloc = b.Id_storage")->result_array();
	}

	public function get_receiving_by_box_id($box_id)
	{
		$this->db->where('id_box', $box_id);
		return $this->db->get('receiving_material')->result_array();
	}

	public function getDetailBox($id_box)
	{
		return $this->db->query("SELECT a.*, b.qty, b.uom, s.Sloc, h.no_box, b.id as id_receiving_material from box_detail a left join receiving_material b on b.id_box_detail = a.id_box_Detail left join storage s on s.Id_storage = b.s_loc LEFT JOIN `box` h on h.id_box = a.id_box  where a.id_box = $id_box")->result_array();
	}



	public function getDetailStorage($id)
	{
		return $this->db->query("SELECT a.*, b.Sloc as sloc_name FROM list_storage a left join storage b on a.sloc = b.Id_storage where a.product_id = '$id'")->result_array();
	}

	public function addNewBox($weight, $sloc, $details)
	{
		// Generate a formatted box number
		$formatted_box_number = $this->generateFormattedBoxNumber();

		$data = [
			'no_box' => $formatted_box_number, // Add the generated box number
			'weight' => $weight,
			'sloc' => $sloc,
		];
		$this->db->insert('box', $data);
		$box_id = $this->db->insert_id();

		foreach ($details as $detail) {
			$detail['id_box'] = $box_id;
			$this->db->insert('box_detail', $detail);
		}
		return $box_id;
	}
	private function generateFormattedBoxNumber()
	{
		// Get the last box number from the database
		$last_box = $this->db->order_by('id_box', 'DESC')->get('box')->row();

		// If there is no previous box number, start with 'CKA000001'
		if (!$last_box) {
			return 'CKA000001';
		}

		// Extract the letter and number parts from the last box number
		$prefix = substr($last_box->no_box, 0, 2); // 'CK'
		$last_char = substr($last_box->no_box, 2, 1); // 'A'
		$last_number = substr($last_box->no_box, 3); // '000001'

		// Increment the number part
		$new_number = (int) $last_number + 1;

		// If the number part reaches 1000000, reset to 1 and increment the letter part
		if ($new_number > 999999) {
			$new_number = 1;
			$last_char++;
		}

		// Format the new box number as 'CKA000001'
		$formatted_box_number = $prefix . $last_char . str_pad($new_number, 6, '0', STR_PAD_LEFT);

		return $formatted_box_number;
	}


	public function updateBox($id_box, $weight, $sloc, $details)
	{
		$data = [
			'weight' => $weight,
			'sloc' => $sloc,
		];
		$this->db->where('id_box', $id_box);
		$this->db->update('box', $data);

		foreach ($details as $detail) {
			$this->db->where('id_detail', $detail['id_detail']);
			$this->db->update('box_detail', $detail);
		}
		return true;
	}



	public function getMaterialReport($id)
	{
		return $this->db->query("SELECT rm.*
		FROM receiving_material rm
		JOIN (
			SELECT reference_number, MAX(id) as max_id
			FROM receiving_material
			WHERE reference_number = 'S0141206243'
			GROUP BY reference_number
		) as grouped_rm ON rm.id = grouped_rm.max_id
		ORDER BY rm.id DESC;")->row();
	}

	public function qtyMaterial($id)
	{
		$result = $this->db->query("SELECT rm.*
		FROM receiving_material rm
		JOIN (
			SELECT reference_number, MAX(id) as max_id
			FROM receiving_material
			WHERE reference_number = 'S0141206243'
			GROUP BY reference_number
		) as grouped_rm ON rm.id = grouped_rm.max_id
		ORDER BY rm.id DESC;");

		if ($result->row()) {
			return $result->row()->qty;
		} else {
			return 0;
		}
	}



	public function deleteMaterialTemp($id)
	{
		$this->db->where('id', $id);
		$delete = $this->db->delete('receiving_material_temp');
		return $delete;
	}

	public function deleteData($table, $where)
	{
		$this->db->where($where);
		$this->db->delete($table);
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function getBoxById($id_box)
	{
		return $this->db->get_where('box', ['id_box' => $id_box])->row_array();
	}

	public function getBoxDetails($id_box)
	{
		return $this->db->get_where('box_detail', ['id_box' => $id_box])->result_array();
	}

}
?>