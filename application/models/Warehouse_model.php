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
		// Query untuk menghitung jumlah sloc dari tabel box dan group by sloc
		$this->db->select('storage.Id_storage, storage.SLoc, storage.space_max, IFNULL(box.space_now, 0) as space_now');
		$this->db->from('storage');
		$this->db->join(
			"(SELECT sloc, COUNT(*) as space_now FROM box GROUP BY sloc) AS box",
			"storage.Id_storage = box.sloc",
			'left'
		);
		$this->db->order_by('storage.Id_storage', 'ASC');
		$storage_data = $this->db->get()->result_array();

		return $storage_data;
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

	public function getListStorageExport()
	{
		$query = "SELECT
			ls.product_id,
			ls.material_desc,
			ls.total_qty,
			b.no_box,
			b.sloc,
			s.SLoc,
			ls.uom,
			sub.total_qty_sum,
			CONCAT('[', s.SLoc, ']-', b.no_box, ':', ls.total_qty) AS box_qty_details
		FROM
			list_storage ls
		JOIN (
			SELECT
				product_id,
				SUM(total_qty) AS total_qty_sum
			FROM
				list_storage
			GROUP BY
				product_id
		) sub ON ls.product_id = sub.product_id
		LEFT JOIN box b ON ls.id_box = b.id_box
		JOIN storage s ON b.sloc = s.Id_storage  -- Use b.sloc from the box table instead of ls.sloc
		ORDER BY
			ls.product_id DESC, ls.id_box
				";

		// $query = "SELECT
		// 		rm.reference_number,
		// 		rm.material,
		// 		rm.qty,
		// 		b.no_box,
		// 		b.sloc,
		// 		s.SLoc,
		// 		rm.uom,
		// 		sub.total_qty_sum,
		// 		CONCAT('[', s.SLoc, ']-', b.no_box, ':', rm.qty) AS box_qty_details
		// 	FROM
		// 		receiving_material rm
		// 	JOIN (
		// 		SELECT
		// 			reference_number,
		// 			SUM(qty) AS total_qty_sum
		// 		FROM
		// 			receiving_material
		// 		GROUP BY
		// 			reference_number
		// 	) sub ON rm.reference_number = sub.reference_number
		// 	LEFT JOIN box b ON rm.id_box = b.id_box
		// 	JOIN storage s ON b.sloc = s.Id_storage
		// 	ORDER BY
		// 		rm.reference_number DESC, rm.id_box";

		return $this->db->query($query)->result_array();
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
		// $query = "SELECT b.*, ls.product_id AS id_material, ls.material_desc, ls.uom, ls.total_qty as qty, ls.id as id_list_storage
		// 	FROM box b
		// 	LEFT JOIN list_storage ls ON ls.id_box = b.id_box
		// 	LEFT JOIN storage s ON s.Id_storage = b.sloc 
		// 	WHERE b.id_box = ?";
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
		$query = "
			SELECT rm.*
			FROM receiving_material rm
			JOIN (
				SELECT reference_number, MAX(id) as max_id
				FROM receiving_material
				WHERE reference_number = ?
				GROUP BY reference_number
			) as grouped_rm ON rm.id = grouped_rm.max_id
			ORDER BY rm.id DESC
		";
		return $this->db->query($query, [$id])->row();
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

	public function deleteMaterial($id)
	{
		// Begin transaction to ensure consistency
		$this->db->trans_start();

		// First, retrieve the necessary information (e.g., id_box and product_id) before deleting the record
		$this->db->select('id_box, id_material');
		$this->db->from('box_detail');
		$this->db->where('id_box_detail', $id);
		$box_detail = $this->db->get()->row();

		if ($box_detail) {
			$id_box = $box_detail->id_box;
			$id_material = $box_detail->id_material;

			// Delete from `box_detail`
			$this->db->where('id_box_detail', $id);
			$delete_box_detail = $this->db->delete('box_detail');

			// Delete related data from `receiving_material`
			$this->db->where('id_box_detail', $id);
			$delete_receiving_material = $this->db->delete('receiving_material');

			// Now, delete from `list_storage`
			// Make sure to use both `id_box` and `id_material` to identify the correct record
			$this->db->where('id_box', $id_box);
			$this->db->where('product_id', $id_material);
			$delete_list_storage = $this->db->delete('list_storage');

			// Commit transaction if all operations are successful
			if ($delete_box_detail && $delete_receiving_material && $delete_list_storage) {
				$this->db->trans_complete();
				return $this->db->trans_status();
			}
		}

		// If we reach here, something went wrong; rollback the transaction
		$this->db->trans_rollback();
		return false;
	}

	public function deleteMaterialBox2($id_box_detail)
	{
		// Begin transaction
		$this->db->trans_start();

		// Delete from `receiving_material`
		$this->db->where('id_box_detail', $id_box_detail);
		$this->db->delete('receiving_material');

		// Delete from `box_detail`
		$this->db->where('id_box_detail', $id_box_detail);
		$this->db->delete('box_detail');

		// Complete transaction
		$this->db->trans_complete();

		// Return transaction status
		return $this->db->trans_status();
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
		$query = "
			SELECT DISTINCT (Id_request) 
			FROM production_request 
			WHERE status = 1
		";
		return $this->db->query($query)->result_array();
	}

	public function getAllReqNoQual()
	{
		return $this->db->get_where('quality_request_detail', array('status_kitting' => 1))->result_array();
	}

	public function production_request()
	{
		$this->db->select('*');
		$this->db->from('production_requests');
		$query = $this->db->get();

		return ($query->num_rows() > 0) ? $query->result_array() : [];
	}


	public function getProductionPlan($Production_plan)
	{
		$query = "SELECT  a.* from production_plan a where a.Production_plan = '$Production_plan'";
		return $this->db->query($query)->row();
	}


	public function getProductionRequestDetailReject($Production_plan)
	{
		$query = "
			SELECT a.*,pd.Material_need, p.Fg_Desc, p.Production_plan_qty, 
				p.status as status_request 
			FROM production_request a 
			LEFT JOIN production_plan p ON p.Production_plan = a.Production_plan 
			LEFT JOIN production_plan_detail pd ON pd.Production_plan = p.Production_plan AND pd.Id_material = a.Id_material 
			WHERE a.Production_plan = ? and p.status = 'REJECTED'
		";
		// print_r($query);die;
		return $this->db->query($query, [$Production_plan])->result_array();
	}



	public function getDataReturnRequest()
	{
		$query = "
			SELECT 
				rw.id,
				rw.id_return,
				rw.no_box,
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
				return_warehouse_detail rwd ON rwd.id_return = rw.id_return
			WHERE 
				rw.status = 1
		";
		return $this->db->query($query)->result_array();
	}

	public function getDataReturnRequestById($id)
	{
		$query = "
			SELECT 
				rw.id,
				rw.id_return,
				rw.no_box,
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
				return_warehouse_detail rwd ON rwd.id_return = rw.id_return
			WHERE 
				rw.status = 1
			AND
				rw.id = ?
		";
		return $this->db->query($query, [$id])->result_array();
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

	public function countAllBoxes()
	{
		return $this->db->query("SELECT a.*, b.SLoc as sloc_name 
			FROM `box` a 
			LEFT JOIN storage b ON a.sloc = b.Id_storage")->num_rows();
	}

	public function getBoxes($limit, $start)
	{
		$this->db->select('a.*, b.SLoc as sloc_name');
		$this->db->from('box a');
		$this->db->join('storage b', 'a.sloc = b.Id_storage', 'left');
		$this->db->limit($limit, $start);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getslocbyweight($weight)
	{
		return $this->db->query("SELECT *
			FROM storage
			WHERE ($weight BETWEEN min_loads AND max_loads)
			-- AND ((Id_storage BETWEEN 1 AND 452) OR (Id_storage IN (454, 455, 456)))
			AND space_max > space_now
			ORDER BY 
			CASE
				WHEN Rack = 'A' THEN 1
				WHEN Rack = 'B' THEN 2
				WHEN Rack = 'C' THEN 3
				WHEN Rack = 'D' THEN 4
				WHEN Rack = 'E' THEN 5
				WHEN Rack = 'F' THEN 6
				WHEN Rack = 'G' THEN 7
				WHEN Rack = 'H' THEN 8
				WHEN Rack = 'I' THEN 9
				WHEN Rack = 'J' THEN 10
				WHEN Rack = 'K' THEN 11
				ELSE 12
			END, 
			SLoc ASC
		")->result_array();
	}

	public function updatedataReturn($table, $id, $Data)
	{
		$this->db->where('id_return', $id);
		$this->db->update($table, $Data);
	}




	public function getOneMaterial($id)
	{
		$this->db->where('id', $id);
		return $this->db->get('receiving_material_temp')->row_array();
	}

	public function getQualityRequest()
	{
		$this->db->select('reject_description, Id_material, Id_request, Material_desc, Material_need, status');
		$query = $this->db->get('quality_request');

		return $query->result_array();
	}


	function getQualityReqDetail($id_request)
	{
		$query = " SELECT a.*, b.Id_material,f.Sloc as sloc_name, c.no_box as box_name, b.Material_desc,d.total_qty as qty_on_box from quality_request_detail a 
		LEFT JOIN quality_request b on b.Id_request = a.Id_Request 
		LEFT JOIN storage f ON f.Id_storage = a.sloc
		LEFT JOIN box c ON c.id_box = a.id_box
		LEFT JOIN list_storage d ON d.sloc = a.sloc and d.id_box = a.id_box and d.product_id = b.Id_material
		 where a.Id_request = ?
		";
		$data = $this->db->query($query, [$id_request])->result_array();
		return $data;
	}


	public function getQualityRequest2($id_request)
	{
		return $this->db->query("SELECT a.* from quality_request a where a.Id_request = '$id_request' ")->row();
	}

	public function getProductionRequestDetail($Production_plan)
	{
		// Mengambil data detail dari beberapa tabel menggunakan LEFT JOIN
		$query = "
        SELECT a.*, 
               pd.Material_need, 
               p.Fg_desc, 
               p.Production_plan_qty, 
               p.status as status_request 
        FROM production_request a
        LEFT JOIN production_plan p ON p.Production_plan = a.Production_plan 
        LEFT JOIN production_plan_detail pd ON pd.Production_plan = p.Production_plan 
            AND pd.Id_material = a.Id_material 
        WHERE a.Production_plan = ?  
        ";

		return $this->db->query($query, [$Production_plan])->result_array();
	}

	// Metode untuk mendapatkan permintaan produksi yang ditolak


	// Metode untuk mendapatkan permintaan produksi yang disetujui
	public function getProductionRequestDetailApprove($Production_plan)
	{
		$query = "
			SELECT a.*, c.no_box, b.SLoc as sloc_name, pd.Material_need, p.Fg_Desc, p.Production_plan_qty, 
				p.status as status_request 
			FROM production_request_approve a 
			LEFT JOIN storage b ON b.Id_storage = a.Sloc  
			LEFT JOIN box c ON c.id_box = a.id_box 
			LEFT JOIN production_plan p ON p.Production_plan = a.Production_plan 
			LEFT JOIN production_plan_detail pd ON pd.Production_plan = p.Production_plan AND pd.Id_material = a.Id_material 
			WHERE a.Production_plan = ?  
		";
		// print_r($query);die;
		return $this->db->query($query, [$Production_plan])->result_array();
	}
	public function getApprovedDetail($production_plan)
	{
		$query = "
        SELECT 
            a.*, 
            b.Id_material, 
            f.Sloc AS sloc_name, 
            c.no_box, 
            b.Material_desc
        FROM 
            production_request a
        LEFT JOIN 
            production_plan b ON b.Production_plan = a.Production_plan
        LEFT JOIN 
            storage f ON f.Id_storage = a.Sloc
        LEFT JOIN 
            box c ON c.id_box = a.id_box
        WHERE 
            a.Production_plan = ?
    ";

		return $this->db->query($query, [$production_plan])->result_array();
	}


	// Fungsi untuk menyetujui rencana produksi dan memperbarui status
	public function approveProduction($production_plan, $sloc)
	{
		$this->db->where('Production_plan', $production_plan);
		$this->db->update('production_request', ['status' => 'APPROVED', 'Sloc' => $sloc]);

		return $this->db->affected_rows() > 0;
	}
	public function getProductionRequest()
	{
		$query = "SELECT  a.* from production_plan a
		";
		return $this->db->query($query)->result_array();
	}

	// Metode untuk mendapatkan permintaan produksi tertentu
	public function getProductionRequest2($Production_plan)
	{
		// Corrected to use the actual $Production_plan parameter dynamically
		return $this->db->query("SELECT a.*, pr.Id_request 
			FROM production_plan a 
			LEFT JOIN production_request pr 
				ON pr.Production_plan COLLATE utf8mb4_general_ci = a.Production_plan COLLATE utf8mb4_general_ci 
			WHERE a.Production_plan COLLATE utf8mb4_general_ci = ?", [$Production_plan])->row();
	}

	// Metode untuk mendapatkan seluruh permintaan produksi
	public function getAllProductionRequests()
	{
		// Mengambil semua data dari tabel production_plan
		return $this->db->get('production_plan')->result_array();
	}
	public function updateBoxQuantity($box_id, $qty_to_reduce)
	{
		// Get the current quantity
		$this->db->set('total_qty', 'total_qty - ' . $qty_to_reduce, FALSE);
		$this->db->where('id_box', $box_id);
		$this->db->update('box');
	}
	public function saveProductionRequestDetail($data)
	{
		// Insert into the production request detail table (or update if necessary)
		$this->db->insert('production_request_detail', [
			'production_plan' => $data['production_plan'],
			'id_material' => $data['id_material'],
			'id_request' => $data['id_request'],
			'qty_need' => $data['qty_need'],
			'box_id' => $data['box'],
			'sloc_id' => $data['sloc']
		]);
	}

}
?>	