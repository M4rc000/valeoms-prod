<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Warehouse_model extends CI_Model
{
	// Metode untuk menghasilkan nomor box yang diformat
	private function generateFormattedBoxNumber()
	{
		$last_box = $this->db->order_by('id_box', 'DESC')->get('box')->row();

		if (!$last_box) {
			return 'CKA000001';
		}

		$prefix = substr($last_box->no_box, 0, 3);
		$last_number = substr($last_box->no_box, 3);
		$new_number = (int) $last_number + 1;

		$formatted_box_number = $prefix . str_pad($new_number, 6, '0', STR_PAD_LEFT);

		return $formatted_box_number;
	}

	public function countListBox()
	{
		return $this->db->count_all('box');
	}


	// Metode untuk menambahkan beberapa box
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


	// Metode untuk mendapatkan daftar semua pengguna
	public function getAllUsers()
	{
		return $this->db->get('user')->result_array();
	}

	// Metode untuk menyisipkan data ke dalam tabel tertentu
	public function insertData($table, $Data)
	{
		return $this->db->insert($table, $Data);
	}

	// Metode untuk mendapatkan detail material berdasarkan ID material
	public function getMaterialDetails($id_material)
	{
		$query = $this->db->get_where('material_list', ['id_material' => $id_material]);
		return $query->result_array();
	}

	// Metode untuk mendapatkan daftar box
	public function getBox()
	{
		return $this->db->get('box')->result_array();
	}

	// Metode untuk mendapatkan material yang diterima
	public function getReceivingMaterials()
	{
		return $this->db->get('receiving_material_temp')->result_array();
	}

	// Metode untuk mendapatkan daftar penyimpanan
	public function getListStorage()
	{
		$query = "
			SELECT
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
				ls.id DESC
		";
		return $this->db->query($query)->result_array();
	}

	public function getMaterialList()
	{
		$this->db->select('Id_material, Material_desc, Uom');
		$this->db->from('material_list');
		$this->db->where('is_active', 1);
		return $this->db->get()->result_array();
	}

	// Metode untuk mendapatkan daftar box beserta nama penyimpanan
	public function getListBox()
	{
		$query = "
			SELECT a.*, b.SLoc as sloc_name 
			FROM `box` a 
			LEFT JOIN storage b ON a.sloc = b.Id_storage
		";
		return $this->db->query($query)->result_array();
	}


	// Metode untuk mendapatkan box berdasarkan ID
	public function getListBoxById($id)
	{
		$query = "
			SELECT a.*, b.SLoc as sloc_name 
			FROM `box` a 
			LEFT JOIN storage b ON a.sloc = b.Id_storage 
			WHERE a.id_box = ?
		";
		return $this->db->query($query, [$id])->row();
	}

	// Metode untuk mendapatkan material yang diterima berdasarkan ID box
	public function get_receiving_by_box_id($box_id)
	{
		$this->db->where('id_box', $box_id);
		return $this->db->get('receiving_material')->result_array();
	}

	// Metode untuk mendapatkan detail box
	public function getDetailBox($id_box)
	{
		$query = "
			SELECT a.*, b.qty, b.uom, s.Sloc, h.no_box, b.id as id_receiving_material 
			FROM box_detail a 
			LEFT JOIN receiving_material b ON b.id_box_detail = a.id_box_Detail 
			LEFT JOIN storage s ON s.Id_storage = b.s_loc 
			LEFT JOIN `box` h ON h.id_box = a.id_box  
			WHERE a.id_box = ?
		";
		return $this->db->query($query, [$id_box])->result_array();
	}

	// Metode untuk mendapatkan detail penyimpanan berdasarkan ID
	public function getDetailStorage($id)
	{
		$query = "
			SELECT a.*, b.Sloc as sloc_name 
			FROM list_storage a 
			LEFT JOIN storage b ON a.sloc = b.Id_storage 
			WHERE a.product_id = ?
		";
		return $this->db->query($query, [$id])->result_array();
	}
	
	// Metode untuk mendapatkan semua penyimpanan (SLoc)
	public function getAllSlocs()
	{
		return $this->db->get('storage')->result_array();
	}

	// Metode untuk menghapus box
	public function deleteBox($id_box)
	{
		$this->db->where('id_box', $id_box);
		$this->db->delete('box_detail');

		$this->db->where('id_box', $id_box);
		$result = $this->db->delete('box');

		return $result;
	}

	// Metode untuk memperbarui box
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

	// Metode untuk mendapatkan laporan material berdasarkan ID
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

	// Metode untuk mendapatkan jumlah material berdasarkan ID
	public function qtyMaterial($id)
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
		$result = $this->db->query($query, [$id]);

		return $result->row() ? $result->row()->qty : 0;
	}

	// Metode untuk mendapatkan nomor box terakhir
	public function getLastBox()
	{
		return $this->db->order_by('id_box', 'DESC')->limit(1)->get('box')->row_array();
	}

	// Metode untuk menghapus data material sementara
	public function deleteMaterialTemp($id)
	{
		$this->db->where('id', $id);
		return $this->db->delete('receiving_material_temp');
	}

	// Metode untuk menghapus detail material dari box
	public function deleteMaterialBox($id)
	{
		$this->db->where('id_box_detail', $id);
		$delete = $this->db->delete('box_detail');

		if ($delete) {
			$this->db->where('id_box_detail', $id);
			return $this->db->delete('receiving_material');
		}

		return false;
	}

	// Metode umum untuk menghapus data dari tabel tertentu
	public function deleteData($table, $where)
	{
		$this->db->where($where);
		$this->db->delete($table);
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	// Metode untuk mendapatkan box berdasarkan ID
	public function getBoxById($id_box)
	{
		$this->db->select('box.*, storage.SLoc as sloc_name');
		$this->db->from('box');
		$this->db->join('storage', 'box.sloc = storage.Id_storage', 'left');
		$this->db->where('box.id_box', $id_box);
		return $this->db->get()->row_array();
	}

	// Metode untuk mendapatkan detail box berdasarkan ID box
	public function getBoxDetails($id_box)
	{
		return $this->db->get_where('box_detail', ['id_box' => $id_box])->result_array();
	}

	// Metode untuk mendapatkan item box berdasarkan ID
	public function getItemBox($id)
	{
		$query = "
			SELECT a.*, b.qty, b.uom, s.Sloc, h.no_box, b.id as id_receiving_material 
			FROM box_detail a 
			LEFT JOIN receiving_material b ON b.id_box_detail = a.id_box_Detail 
			LEFT JOIN storage s ON s.Id_storage = b.s_loc 
			LEFT JOIN `box` h ON h.id_box = a.id_box  
			WHERE a.id_box_detail = ?
		";
		return $this->db->query($query, [$id])->row();
	}

	// Metode untuk mendapatkan semua permintaan produksi yang belum selesai
	public function getAllReqNoProd()
	{
		$query = "
			SELECT DISTINCT (Id_request) 
			FROM production_request 
			WHERE status = 1
		";
		return $this->db->query($query)->result_array();
	}

	// Metode untuk mendapatkan semua permintaan kualitas yang belum selesai
	public function getAllReqNoQual()
	{
		return $this->db->get_where('quality_request', ['status' => 1])->result_array();
	}

	// Metode untuk mendapatkan permintaan produksi
	public function production_request()
	{
		$this->db->select('*');
		$this->db->from('production_requests');
		$query = $this->db->get();

		return ($query->num_rows() > 0) ? $query->result_array() : [];
	}

	// Metode untuk mendapatkan permintaan produksi berdasarkan rencana produksi
	public function getProductionRequest()
	{
		$query = "
			SELECT a.id, a.Id_request, a.Production_plan, a.Id_material, a.Material_desc, a.Qty, a.Sloc, a.id_box, a.status, a.sloc_before, 
				c.no_box, b.SLoc as sloc_name, p.Fg_Desc, SUM(p.Production_plan_qty) as Production_plan_qty, pd.Material_need, 
				p.status as status_request, b2.SLoc as sloc_name_before 
			FROM production_request a 
			LEFT JOIN storage b ON b.Id_storage = a.Sloc 
			LEFT JOIN storage b2 ON b2.Id_storage = a.sloc_before 
			LEFT JOIN box c ON c.id_box = a.id_box 
			LEFT JOIN production_plan p ON p.Production_plan = a.Production_plan 
			LEFT JOIN production_plan_detail pd ON pd.Production_plan = p.Production_plan AND pd.Id_material = a.Id_material 
			GROUP BY a.id, a.Id_request, a.Production_plan, a.Id_material, a.Material_desc, a.Qty, a.Sloc, a.id_box, a.status, a.sloc_before, 
				c.no_box, b.SLoc, p.Fg_Desc, pd.Material_need, p.status, b2.SLoc 
			ORDER BY a.id DESC
		";
		return $this->db->query($query)->result_array();
	}

	// Metode untuk mendapatkan detail permintaan produksi berdasarkan rencana produksi
	public function getProductionRequestDetail($Production_plan)
	{
		$query = "
			SELECT a.*, c.no_box, b.SLoc as sloc_name, b2.SLoc as sloc_name_before, pd.Material_need, p.Fg_Desc, p.Production_plan_qty, 
				p.status as status_request 
			FROM production_request a 
			LEFT JOIN storage b ON b.Id_storage = a.Sloc  
			LEFT JOIN storage b2 ON b2.Id_storage = a.sloc_before 
			LEFT JOIN box c ON c.id_box = a.id_box 
			LEFT JOIN production_plan p ON p.Production_plan = a.Production_plan 
			LEFT JOIN production_plan_detail pd ON pd.Production_plan = p.Production_plan AND pd.Id_material = a.Id_material 
			WHERE a.Production_plan = ?
		";
		return $this->db->query($query, [$Production_plan])->result_array();
	}

	// Metode untuk mendapatkan permintaan pengembalian
	public function getDataReturnRequest()
	{
		$query = "
			SELECT 
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
				return_warehouse_detail rwd ON rwd.id_return = rw.id_return
			WHERE 
				rw.status = 1
		";
		return $this->db->query($query)->result_array();
	}

	// Metode untuk mendapatkan permintaan pengembalian berdasarkan ID
	public function getDataReturnRequestById($id)
	{
		$query = "
			SELECT 
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
				return_warehouse_detail rwd ON rwd.id_return = rw.id_return
			WHERE 
				rw.status = 1
			AND
				rw.id = ?
		";
		return $this->db->query($query, [$id])->result_array();
	}

	// Metode untuk mendapatkan nomor box terakhir
	public function getLastNoBox()
	{
		$last_box = $this->db->order_by('id_box', 'DESC')->get('box')->row();

		if (!$last_box) {
			return 'CKA00001';
		}

		$prefix = substr($last_box->no_box, 0, 3);
		$last_number = substr($last_box->no_box, 3);

		$new_number = (int) $last_number + 1;
		$formatted_box_number = $prefix . str_pad($new_number, 6, '0', STR_PAD_LEFT);

		return $formatted_box_number;
	}

	// Metode untuk memperbarui data
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

	public function getProductionRequest2($Production_plan)
	{
		// Assuming you have a table named 'production_requests' in your database
		return $this->db->query("SELECT a.*, pr.Id_request from production_plan a LEFT JOIN production_request pr on pr.Production_plan = a.Production_plan where a.Production_plan = '$Production_plan'")->row();
}
}
?>