<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Warehouse_model extends CI_Model
{

	public function updateSpaceNow()
	{
		// Get the count of boxes grouped by sloc
		$query = $this->db->query("SELECT sloc, COUNT(*) as count FROM box WHERE sloc IS NOT NULL GROUP BY sloc");
		$box_counts = $query->result_array();

		// Reset space_now for all storage
		$this->db->update('storage', ['space_now' => 0]);

		// Update space_now based on the count of boxes
		foreach ($box_counts as $box_count) {
			$this->db->where('Id_storage', $box_count['sloc']);
			$this->db->update('storage', ['space_now' => $box_count['count']]);
		}
	}

	// Function to increment space_now
	public function incrementSpace($sloc)
	{
		$this->db->where('Id_storage', $sloc);
		$this->db->set('space_now', 'space_now + 1', FALSE);
		$this->db->update('storage');
	}

	// Function to decrement space_now
	public function decrementSpace($sloc)
	{
		$this->db->where('Id_storage', $sloc);
		$this->db->set('space_now', 'space_now - 1', FALSE);
		$this->db->update('storage');
	}

	// Function to delete box and update space_now
	public function deleteBox($id_box)
	{
		$this->db->trans_start();

		// Get the sloc for the box
		$this->db->select('sloc');
		$this->db->from('box');
		$this->db->where('id_box', $id_box);
		$sloc = $this->db->get()->row()->sloc;

		// Delete the box
		$this->db->where('id_box', $id_box);
		$this->db->delete('box');

		// Update space_now
		if ($sloc) {
			$this->decrementSpace($sloc);
		}

		$this->db->trans_complete();

		return $this->db->trans_status();
	}

	public function checkSpaceAvailability($id_sloc)
	{
		$this->db->select('space_now, space_max');
		$this->db->from('storage');
		$this->db->where('Id_storage', $id_sloc);
		$result = $this->db->get()->row();

		if ($result && $result->space_now < $result->space_max) {
			return true;
		}
		return false;
	}

	public function getSLocAvailability()
	{
		$this->db->select('Id_storage, SLoc, space_now, space_max');
		$query = $this->db->get('storage');
		return $query->result_array();
	}


	public function sloc_availability()
	{
		$data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
		$data['sloc_availability'] = $this->WModel->getSLocAvailability();

		$data['title'] = 'SLoc Availability';

		$this->load->view('templates/header', $data);
		$this->load->view('templates/navbar', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('warehouse/sloc_availability', $data);
		$this->load->view('templates/footer');
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
		$prefix = substr($last_box->no_box, 0, 3); // 'CKA'
		$last_number = substr($last_box->no_box, 3); // '000001'

		// Increment the number part
		$new_number = (int) $last_number + 1;

		// Format the new box number as 'CKA00001'
		$formatted_box_number = $prefix . str_pad($new_number, 6, '0', STR_PAD_LEFT);

		return $formatted_box_number;
	}

	public function addMultipleBoxes($weight, $sloc, $box_type, $details, $number_of_boxes)
	{
		$box_ids = [];
		for ($i = 0; $i < $number_of_boxes; $i++) {
			$formatted_box_number = $this->generateFormattedBoxNumber();

			$data = [
				'no_box' => $formatted_box_number,
				'weight' => $weight,
				'sloc' => $sloc,
				'box_type' => $box_type,
			];
			$this->db->insert('box', $data);
			$box_id = $this->db->insert_id();
			$box_ids[] = $formatted_box_number;

			if (!empty($details)) {
				foreach ($details as $detail) {
					$detail['id_box'] = $box_id;
					$this->db->insert('box_detail', $detail);
				}
			}
		}
		return $box_ids;
	}



	public function getAllUsers()
	{
		return $this->db->get('user')->result_array();
	}

	public function insertData($table, $Data)
	{
		return $this->db->insert($table, $Data);
	}

	public function getMaterialList()
	{
		$this->db->select('Id_material, Material_desc, Uom');
		$this->db->from('material_list');
		$this->db->where('is_active', 1);
		return $this->db->get()->result_array();
	}

	public function getMaterialist()
	{
		return $this->db->get('material_list')->result();
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
			ls.id,
			ls.product_id,
			ls.material_desc,
			ls.total_qty,
			sub.total_qty AS total_qty_sum
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
		GROUP BY
			ls.id, ls.product_id, ls.total_qty, sub.total_qty
		ORDER BY
			ls.id DESC")->result_array();
	}

	public function getListBox()
	{
		$query = "
			SELECT a.*, b.SLoc as sloc_name 
			FROM `box` a 
			LEFT JOIN storage b ON a.sloc = b.Id_storage
		";
		return $this->db->query($query)->result_array();
	}

	public function getListBoxById($id)
	{
		$query = "SELECT a.*, b.SLoc as sloc_name FROM `box` a LEFT JOIN storage b ON a.sloc = b.Id_storage WHERE a.id_box = ?";
		return $this->db->query($query, array($id))->row();
	}

	public function get_receiving_by_box_id($box_id)
	{
		$this->db->where('id_box', $box_id);
		return $this->db->get('receiving_material')->result_array();
	}

	public function getDetailBox($id_box)
	{
		$query = "SELECT a.*, b.qty, b.uom, s.Sloc, h.no_box, b.id as id_receiving_material 
			FROM box_detail a 
			LEFT JOIN receiving_material b ON b.id_box_detail = a.id_box_Detail 
			LEFT JOIN storage s ON s.Id_storage = b.s_loc 
			LEFT JOIN `box` h ON h.id_box = a.id_box  
			WHERE a.id_box = ?";
		return $this->db->query($query, array($id_box))->result_array();
	}

	public function getDetailStorage($id)
	{
		$query = "SELECT a.*, b.Sloc as sloc_name 
			FROM list_storage a 
			LEFT JOIN storage b ON a.sloc = b.Id_storage 
			WHERE a.product_id = ?";
		return $this->db->query($query, array($id))->result_array();
	}

	public function getAllSlocs()
	{
		return $this->db->get('storage')->result_array();
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
			if (isset($detail['id_detail'])) {
				$this->db->where('id_detail', $detail['id_detail']);
				$this->db->update('box_detail', $detail);
			} else {
				$detail['id_box'] = $id_box;
				$this->db->insert('box_detail', $detail);
			}
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
            WHERE reference_number = '$id'
            GROUP BY reference_number
        ) as grouped_rm ON rm.id = grouped_rm.max_id
        ORDER BY rm.id DESC;")->row();
	}

	public function getMaterialReportLAST($id)
	{
		return $this->db->query("SELECT rm.*
        FROM receiving_material rm
       	WHERE rm.reference_number = '$id' ORDER BY id desc LIMIT 2")->result();
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

	public function getLastBox()
	{
		return $this->db->order_by('id_box', 'DESC')->limit(1)->get('box')->row_array();
	}



	public function deleteMaterialTemp($id)
	{
		$this->db->where('id', $id);
		$delete = $this->db->delete('receiving_material_temp');
		return $delete;
	}

	public function deleteMaterialBox($id)
	{
		$this->db->where('id_box_detail', $id);
		$delete = $this->db->delete('box_detail');
		if ($delete) {
			$this->db->where('id_box_detail', $id);
			$delete_material = $this->db->delete('receiving_material');
		}
		return $delete_material;
	}

	public function deleteData($table, $where)
	{
		$this->db->where($where);
		$this->db->delete($table);
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function getBoxById($id_box)
	{
		$this->db->select('box.*, storage.SLoc as sloc_name');
		$this->db->from('box');
		$this->db->join('storage', 'box.sloc = storage.Id_storage', 'left');
		$this->db->where('box.id_box', $id_box);
		return $this->db->get()->row_array();
	}

	public function getBoxDetails($id_box)
	{
		return $this->db->get_where('box_detail', ['id_box' => $id_box])->result_array();
	}

	public function getItemBox($id)
	{
		return $this->db->query("SELECT a.*, b.qty, b.uom, s.Sloc, h.no_box, b.id as id_receiving_material from box_detail a left join receiving_material b on b.id_box_detail = a.id_box_Detail left join storage s on s.Id_storage = b.s_loc LEFT JOIN `box` h on h.id_box = a.id_box  where a.id_box_detail = $id")->row();
	}

	public function getAllReqNoProd()
	{
		return $this->db->query("SELECT DISTINCT (Id_request) FROM production_request")->result_array();
	}

	public function getAllReqNoQual()
	{
		return $this->db->get_where('quality_request', array('status' => 1))->result_array();
	}

	public function production_request()
	{
		// Assuming you have a table named 'production_requests' containing production request data
		$this->db->select('*');
		$this->db->from('production_requests');
		// Add any additional conditions or filters here if needed
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->result_array(); // Return the results as an array of rows
		} else {
			return array(); // Return an empty array if no results found
		}
	}
	public function getProductionRequest()
	{
		// Assuming you have a table named 'production_request' in your database
		return $this->db->query('SELECT a.id, a.Id_request, a.Production_plan, a.Id_material, a.Material_desc, a.Qty, a.Sloc, a.id_box, a.status, a.sloc_before, c.no_box, b.SLoc as sloc_name , p.Fg_Desc, SUM(p.Production_plan_qty) as Production_plan_qty, pd.Material_need, p.status as status_request, b2.SLoc as sloc_name_before FROM production_request a LEFT JOIN storage b on b.Id_storage = a.Sloc LEFT JOIN storage b2 on b2.Id_storage = a.sloc_before LEFT JOIN box c on c.id_box = a.id_box LEFT JOIN production_plan p on p.Production_plan = a.Production_plan LEFT JOIN production_plan_detail pd on pd.Production_plan = p.Production_plan and pd.Id_material = a.Id_material group by a.id, a.Id_request, a.Production_plan, a.Id_material, a.Material_desc, a.Qty, a.Sloc, a.id_box, a.status, a.sloc_before, c.no_box, b.SLoc, p.Fg_Desc, pd.Material_need, p.status, b2.SLoc order by a.id desc')->result_array();
	}


	public function getProductionRequest2($Production_plan)
	{
		// Assuming you have a table named 'production_requests' in your database
		return $this->db->query("SELECT a.*, pr.Id_request from production_plan a LEFT JOIN production_request pr on pr.Production_plan = a.Production_plan where a.Production_plan = '$Production_plan'")->row();
	}

	public function getProductionRequestDetail($Production_plan)
	{
		// Assuming you have a table named 'production_requests' in your database
		return $this->db->query("SELECT a.*, c.no_box, b.SLoc as sloc_name, b2.SLoc as sloc_name_before, pd.Material_need, p.Fg_Desc, p.Production_plan_qty, p.status as status_request FROM production_request a LEFT JOIN storage b on b.Id_storage = a.Sloc  LEFT JOIN storage b2 on b2.Id_storage = a.sloc_before LEFT JOIN box c on c.id_box = a.id_box LEFT JOIN production_plan p on p.Production_plan = a.Production_plan LEFT JOIN production_plan_detail pd on pd.Production_plan = p.Production_plan and pd.Id_material = a.Id_material where a.Production_plan = '$Production_plan' ")->result_array();
	}

	public function getDataReturnRequest()
	{
		return $this->db->query("SELECT 
			rw.id,
			rw.id_return,
			rw.box_type,
			rw.box_weight,
			rw.status,
			rwd.Id_material,
			rwd.Material_desc,
			rwd.Material_qty,
			rwd.Material_uom,
			rw.Crtdt
			FROM 
				return_warehouse rw
			JOIN
				return_warehouse_detail rwd
				ON rwd.id_return = rw.id_return
			WHERE 
				rw.status = 1")->result_array();
	}

	public function getDataReturnRequestById($id)
	{
		return $this->db->query("SELECT 
			rw.id,
			rw.id_return,
			rw.box_type,
			rw.box_weight,
			rw.status,
			rwd.Id_material,
			rwd.Material_desc,
			rwd.Material_qty,
			rwd.Material_uom,
			rw.Crtdt
			FROM 
				return_warehouse rw
			JOIN
				return_warehouse_detail rwd
				ON rwd.id_return = rw.id_return
			WHERE 
				rw.status = 1
			AND
				rw.id = '$id'")->result_array();
	}

	public function getLastNoBox()
	{
		$last_box = $this->db->order_by('id_box', 'DESC')->get('box')->row();

		if (!$last_box) {
			return 'CKA00001';
		}

		$prefix = substr($last_box->no_box, 0, 2);
		$last_char = substr($last_box->no_box, 2, 1);
		$last_number = substr($last_box->no_box, 3);

		$new_number = (int) $last_number + 1;

		if ($new_number > 99999) {
			$new_number = 1;
			$last_char++;
		}

		$formatted_box_number = $prefix . $last_char . str_pad($new_number, 6, '0', STR_PAD_LEFT);

		return $formatted_box_number;
	}

	public function updatedata($id, $table, $Data)
	{
		$this->db->where('id', $id);
		$this->db->update($table, $Data);
	}

	public function getslocbyweight($weight)
	{
		return $this->db->query("SELECT *
			FROM storage
			WHERE ($weight BETWEEN min_loads AND max_loads)
			AND ((Id_storage BETWEEN 1 AND 452) OR (Id_storage IN (454, 455, 456)))
			AND space_max > space_now")->result_array();
	}

	public function updatedataReturn($table, $id, $Data)
	{
		$this->db->where('id_return', $id);
		$this->db->update($table, $Data);
	}
}
?>