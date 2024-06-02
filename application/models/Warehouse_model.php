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

	public function getMaterialReport($id)
	{
		return $this->db->query("SELECT * FROM receiving_material where reference_number = '$id' group by reference_number order by id desc")->row();
	}

	public function qtyMaterial($id)
	{
		$result = $this->db->query("SELECT * FROM receiving_material WHERE reference_number = '$id' ORDER BY id DESC LIMIT 1 OFFSET 1");

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
}
?>