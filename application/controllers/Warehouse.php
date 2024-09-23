<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');
use Dompdf\Dompdf;
use Dompdf\Exception;

class Warehouse extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		is_logged_in();
		$this->load->library('form_validation');
		$this->load->model('Warehouse_model');
		$this->load->model('Admin_model', 'Amodel');
		$this->load->model('Warehouse_model', 'WModel');
		$this->load->model('Production_model', 'PModel');
	}

	private function set_flashdata($type, $message)
	{
		$this->session->set_flashdata('SUCCESS', "<div class=\"alert alert-$type alert-dismissible fade show mb-2\" id=\"dismiss\" role=\"alert\" style=\"width: 40%\">
			<i class=\"bi bi-check-circle me-1\"></i> $message
			<button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\" aria-label=\"Close\"></button>
			</div>");
	}


	public function AddReceivingMaterial()
	{
		$reference_number = $this->input->post('reference_number');
		$uomname = $this->input->post('uom');
		$material = $this->input->post('material');

		$data = array(
			'reference_number' => $reference_number,
			'material_desc' => (empty($material) ? '' : $material),
			'qty' => $this->input->post('qty'),
			'uom' => (empty($uomname) ? '' : $uomname),
			'receiving_date' => $this->input->post('receiving_date'),
			'created_at' => date('Y-m-d H:i:s')
		);

		$this->Amodel->insertData('receiving_material_temp', $data);

		$this->session->set_flashdata('SUCCESS', '<div class="alert alert-success alert-dismissible fade show mb-2" id="dismiss" role="alert" style="width: 40%">
			<i class="bi bi-check-circle me-1"></i> New receiving material successfully added
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>');

		redirect('Warehouse/');
	}

	public function production_request()
	{
		$data['production_request'] = $this->WModel->getProductionRequest();
		$data['users'] = $this->WModel->getAllUsers();
		$this->load_common_views('Production Request', 'warehouse/production_request', $data);
	}

	private function load_common_views($title, $view, $data = [])
	{
		$data['title'] = $title;
		$data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
		$data['name'] = $this->db->get_where('user', ['name' => $this->session->userdata('name')])->row_array();
		$this->load->view('templates/header', $data);
		$this->load->view('templates/navbar', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view($view, $data);
		$this->load->view('templates/footer');
	}
	public function approve_production_plan($production_plan)
	{
		$data['production_request'] = $this->WModel->getProductionRequestDetail($production_plan);
		$data['production_plan'] = $this->WModel->getProductionPlan($production_plan);
		$data['users'] = $this->WModel->getAllUsers();

		$this->load_common_views('Approve Production Request', 'warehouse/approve_production_request', $data);
	}
	public function get_detail_approve_pr()
	{
		$Production_plan_detail_id = $this->input->post('Production_plan_detail_id');
		$query = "
			SELECT a.*, b.Sloc as sloc_name, c.no_box as box_name, d.total_qty as qty_on_box
			FROM production_request_approve a
			LEFT JOIN storage b ON b.Id_storage = a.Sloc
			LEFT JOIN box c ON c.id_box = a.id_box
			LEFT JOIN list_storage d ON d.sloc = a.Sloc and d.id_box = a.id_box and d.product_id = a.Id_material
			WHERE Production_plan_detail_id = ?
		";
		// Execute query with parameter binding
		$data = $this->db->query($query, [$Production_plan_detail_id])->result_array();
		echo json_encode($data);

	}

	public function rejectProductionRequest()
	{
		// Ambil production_plan dan reject_description dari input post
		$production_plan = $this->input->post('production_plan');
		$reject_description = $this->input->post('reject_description'); // Ambil reject description dari form
		
		// Mulai transaksi untuk memastikan konsistensi
		$this->db->trans_start();
	
		// Ambil data request yang sesuai dengan production_plan
		$data_request = $this->db->query("SELECT * FROM production_request WHERE Production_plan = ?", [$production_plan])->result();
	
		// Looping untuk setiap data request
		foreach ($data_request as $v) {
			$id_material = $v->Id_material;
			$sloc = $v->Sloc;
			$id_box = $v->id_box;
			$qty = $v->Qty;
	
			// Pastikan id_box tidak null sebelum melakukan update ke list_storage
			if ($id_box) {
				// Update jumlah qty di list_storage
				$this->db->query(
					"UPDATE list_storage SET total_qty_real = total_qty_real + ? WHERE product_id = ? AND sloc = ? AND id_box = ?",
					array($qty, $id_material, $sloc, $id_box)
				);
			}
		}
	
		// Update status ke REJECTED dan tambahkan reject_description di production_request tanpa mengubah SLoc
		$this->db->where('Production_plan', $production_plan);
		$this->db->update('production_request', [
			'status' => 'REJECTED',
			'reject_description' => $reject_description // Simpan reject description
		]);
	
		// Update status ke REJECTED di production_plan
		$this->db->where('Production_plan', $production_plan);
		$this->db->update('production_plan', ['status' => 'REJECTED']);
	
		// Selesaikan transaksi dan cek status
		$this->db->trans_complete();
	
		// Cek apakah transaksi berhasil
		if ($this->db->trans_status() === FALSE) {
			// Jika ada kegagalan, rollback perubahan dan kembalikan status false
			echo json_encode(['status' => false, 'message' => 'Failed to reject the request']);
		} else {
			// Jika sukses, commit perubahan dan kembalikan status true
			echo json_encode(['status' => true, 'message' => 'Request rejected successfully', 'production_plan' => $production_plan]);
		}
	}
	
	public function getApprovedDetail($production_plan)
	{
		$query = "
			SELECT a.*, b.Id_material, f.Sloc AS sloc_name, c.no_box AS box_name, b.Material_desc
			FROM production_request a
			LEFT JOIN production_plan b ON b.Production_plan = a.Production_plan
			LEFT JOIN storage f ON f.Id_storage = a.Sloc
			LEFT JOIN box c ON c.id_box = a.id_box
			WHERE a.Production_plan = ?
		";
		$data = $this->db->query($query, [$production_plan])->result_array();
		return $data;
	}

	public function approveProductionRequest()
	{
		// Ambil data dari input POST
		$production_plan = $this->input->post('production_plan', true);
		$sloc = $this->input->post('sloc', true); // Ambil sloc dari input form atau database
		
		// Mulai transaksi untuk memastikan konsistensi data
		$this->db->trans_start();
	
		// Ambil data dari tabel production_request_approve untuk rencana produksi yang diapprove
		$data_request = $this->db->query("SELECT * FROM production_request_approve WHERE Production_plan = ?", [$production_plan])->result();
	
		// Looping untuk mengurangi total_qty dari list_storage
		foreach ($data_request as $v) {
			$this->db->query(
				"UPDATE list_storage SET total_qty = total_qty - ? WHERE product_id = ? AND sloc = ? AND id_box = ?",
				array($v->Qty, $v->Id_material, $v->Sloc, $v->id_box)
			);
		}
	
		// Update status produksi menjadi APPROVED dan menyimpan SLoc
		$this->db->where('Production_plan', $production_plan);
		$this->db->update('production_request', ['status' => 'APPROVED', 'Sloc' => $sloc]);
	
		// Update status di tabel production_plan
		$this->db->where('Production_plan', $production_plan);
		$this->db->update('production_plan', ['status' => 'APPROVED']);
	
		// Selesaikan transaksi
		$this->db->trans_complete();
	
		// Cek apakah transaksi berhasil
		if ($this->db->trans_status() === FALSE) {
			// Jika ada kegagalan, rollback perubahan dan kembalikan status false
			echo json_encode(['status' => false, 'message' => 'Failed to approve production plan']);
		} else {
			// Jika sukses, commit perubahan dan kembalikan status true
			echo json_encode(['status' => true, 'message' => 'Production plan approved successfully']);
		}
	}
	
	

	public function rejectQualityRequest()
	{
		$id_request = $this->input->post('id_request', true);
		$reject_description = $this->input->post('reject_description', true);

		// $data_request = $this->db->query("SELECT * from production_rapproveequest where Quality_plan = ?", [$id_request])->result();
		$data_request_approve = $this->WModel->getQualityRequestDetail($id_request);
		if ($data_request_approve) {
			foreach ($data_request_approve as $v) {
				$this->db->query("UPDATE list_storage SET total_qty_real = total_qty_real + ? WHERE product_id = ? AND sloc = ? AND id_box = ?", array($v['qty_unpack'], $v['Id_material'], $v['sloc'], $v['id_box']));
			}
		}

		// status  0 = not yet approved, status 1 = approved, status 3 = rejected.
		$rejected1 = $this->db->query("UPDATE quality_request SET status = '3' WHERE id_request = ?", [$id_request]);
		$rejected2 = $this->db->query("UPDATE quality_request SET reject_description = '$reject_description' WHERE id_request = ?", [$id_request]);

		$response = ($rejected1 && $rejected2) ? ['status' => true] : ['status' => false];
		echo json_encode($response);
	}

	public function print_request($production_plan)
	{
		$time = date('dmY');
		$data['header'] = $this->Warehouse_model->getProductionRequest2($production_plan);
		$data['detail'] = $this->Warehouse_model->getProductionRequestDetail($production_plan);

		$namafile = "Production-Request-" . $production_plan . '-' . $time;
		$dompdf = new Dompdf(array('enable_remote' => true));
		$html = $this->load->view('warehouse/print_request', $data, true);

		$dompdf->loadHtml($html);
		$dompdf->setPaper('A4', 'potrait');
		$dompdf->render();
		$dompdf->stream($namafile . ".pdf", array('Attachment' => 0));

	}
	public function print_request_reject($production_plan)
	{
		$time = date('dmY');
		$data['header'] = $this->WModel->getProductionRequest2($production_plan);
		$data['detail'] = $this->WModel->getProductionRequestDetailReject($production_plan);

		$namafile = "Production-Request-" . $production_plan . '-' . $time;
		$dompdf = new Dompdf(['enable_remote' => true]);
		$html = $this->load->view('warehouse/print_request_reject', $data, true);

		$dompdf->loadHtml($html);
		$dompdf->setPaper('A4', 'portrait');
		$dompdf->render();
		$dompdf->stream($namafile . ".pdf", ['Attachment' => 0]);
	}

	public function addItemBox()
	{
		$id_box = $this->input->post('id_box');
		$uomname = $this->input->post('uom');
		$material = $this->input->post('material_desc');

		$data = array(
			'id_box' => $id_box,
			'id_material' => $this->input->post('reference_number'),
			'material_desc' => $material,
			'crtby' => $this->session->userdata('username'),
			'crtdt' => date('Y-m-d H:i:s')
		);

		// Logging data for debugging
		log_message('info', 'addItemBox data: ' . print_r($data, true));

		$save_detail = $this->Amodel->insertData('box_detail', $data);

		$id_box_detail = $this->db->insert_id();

		if ($save_detail) {

			$receiving_material = array(
				'reference_number' => $this->input->post('reference_number'),
				'id_box' => $id_box,
				'id_box_detail' => $id_box_detail,
				'material' => (empty($material) ? '' : $material),
				'qty' => $this->input->post('qty'),
				's_loc' => $this->input->post('id_sloc'),
				'uom' => (empty($uomname) ? '' : $uomname),
				'created_at' => date('Y-m-d H:i:s'),
				'created_by' => $this->session->userdata('username')
			);

			// Logging data for debugging
			log_message('info', 'addItemBox receiving_material: ' . print_r($receiving_material, true));

			$save = $this->Amodel->insertData('receiving_material', $receiving_material);

			$list_storage = [
				'id_box' => $id_box,
				'product_id' => $this->input->post('reference_number'),
				'material_desc' => $material,
				'total_qty' => $this->input->post('qty'),
				'total_qty_real' => $this->input->post('qty'),
				'sloc' => $this->input->post('id_sloc'),
				'uom' => $this->input->post('uom'),
				'created_by' => $this->session->userdata('username'),
				'created_at' => date('Y-m-d H:i:s')
			];
			$this->Amodel->insertData('list_storage', $list_storage);

			if ($save) {
				$this->session->set_flashdata('SUCCESS', '<div class="alert alert-success alert-dismissible fade show mb-2" id="dismiss" role="alert" style="width: 40%">
                <i class="bi bi-check-circle me-1"></i> New material successfully added
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>');
				redirect('Warehouse/edit_box_view/' . $id_box);
			}
		}
	}

	public function addMaterialCc()
	{
		$id_box = $this->input->post('id_box');
		$uomname = $this->input->post('uom');
		$material = $this->input->post('material_desc');

		$data = array(
			'id_box' => $id_box,
			'id_material' => $this->input->post('reference_number'),
			'material_desc' => $material,
			'crtby' => $this->session->userdata('username'),
			'crtdt' => date('Y-m-d H:i:s')
		);

		// Logging data for debugging
		log_message('info', 'addItemBox data: ' . print_r($data, true));

		$save_detail = $this->Amodel->insertData('box_detail', $data);

		$id_box_detail = $this->db->insert_id();

		if ($save_detail) {

			$receiving_material = array(
				'reference_number' => $this->input->post('reference_number'),
				'id_box' => $id_box,
				'id_box_detail' => $id_box_detail,
				'material' => (empty($material) ? '' : $material),
				'qty' => $this->input->post('qty'),
				's_loc' => $this->input->post('id_sloc'),
				'uom' => (empty($uomname) ? '' : $uomname),
				'created_at' => date('Y-m-d H:i:s'),
				'created_by' => $this->session->userdata('username')
			);

			// Logging data for debugging
			log_message('info', 'addItemBox receiving_material: ' . print_r($receiving_material, true));

			$save = $this->Amodel->insertData('receiving_material', $receiving_material);

			$list_storage = [
				'id_box' => $id_box,
				'product_id' => $this->input->post('reference_number'),
				'material_desc' => $material,
				'total_qty' => $this->input->post('qty'),
				'total_qty_real' => $this->input->post('qty'),
				'sloc' => $this->input->post('id_sloc'),
				'uom' => $this->input->post('uom'),
				'created_by' => $this->session->userdata('username'),
				'created_at' => date('Y-m-d H:i:s')
			];
			$this->Amodel->insertData('list_storage', $list_storage);

			if ($save) {
				$this->session->set_flashdata('SUCCESS', '<div class="alert alert-success alert-dismissible fade show mb-2" id="dismiss" role="alert" style="width: 40%">
                <i class="bi bi-check-circle me-1"></i> New material successfully added
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>');
				redirect('Warehouse/cycle_box_view/' . $id_box);
			}
		}
	}



	public function addItemBoxCyle()
	{
		$id_box = $this->input->post('id_box');
		$uomname = $this->input->post('uom');
		$material = $this->input->post('material_desc');

		$data = array(
			'id_box' => $id_box,
			'id_material' => $this->input->post('reference_number'),
			'material_desc' => $material,
			'crtby' => $this->session->userdata('username'),
			'crtdt' => date('Y-m-d H:i:s')
		);

		// Logging data for debugging
		log_message('info', 'addItemBox data: ' . print_r($data, true));

		$save_detail = $this->Amodel->insertData('box_detail', $data);

		$id_box_detail = $this->db->insert_id();

		if ($save_detail) {

			$receiving_material = array(
				'reference_number' => $this->input->post('reference_number'),
				'id_box' => $id_box,
				'id_box_detail' => $id_box_detail,
				'material' => (empty($material) ? '' : $material),
				'qty' => $this->input->post('qty'),
				's_loc' => $this->input->post('id_sloc'),
				'uom' => (empty($uomname) ? '' : $uomname),
				'created_at' => date('Y-m-d H:i:s'),
				'created_by' => $this->session->userdata('username')
			);

			// Logging data for debugging
			log_message('info', 'addItemBox receiving_material: ' . print_r($receiving_material, true));

			$save = $this->Amodel->insertData('receiving_material', $receiving_material);

			$list_storage = [
				'id_box' => $id_box,
				'product_id' => $this->input->post('reference_number'),
				'material_desc' => $material,
				'total_qty' => $this->input->post('qty'),
				'total_qty_real' => $this->input->post('qty'),
				'sloc' => $this->input->post('id_sloc'),
				'uom' => $this->input->post('uom'),
				'created_by' => $this->session->userdata('username'),
				'created_at' => date('Y-m-d H:i:s')
			];
			$this->Amodel->insertData('list_storage', $list_storage);

			if ($save) {
				$this->session->set_flashdata('SUCCESS', '<div class="alert alert-success alert-dismissible fade show mb-2" id="dismiss" role="alert" style="width: 40%">
                <i class="bi bi-check-circle me-1"></i> New material successfully added
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>');
				redirect('Warehouse/edit_box_view/' . $id_box);
			}
		}
	}

	public function editReceivingMaterialTemp()
	{
		$id = $this->input->post('id');
		$reference_number = $this->input->post('reference_number');
		$uomname = $this->input->post('uom');
		$material = $this->input->post('material');

		$data = array(
			'reference_number' => $reference_number,
			'material_desc' => (empty($material) ? '' : $material),
			'qty' => $this->input->post('qty'),
			'uom' => (empty($uomname) ? '' : $uomname),
			'receiving_date' => $this->input->post('receiving_date'),
			'updated_at' => date('Y-m-d H:i:s')
		);

		$this->Amodel->updateData('receiving_material_temp', $id, $data);

		$this->session->set_flashdata('SUCCESS', '<div class="alert alert-success alert-dismissible fade show mb-2" id="dismiss" role="alert" style="width: 40%">
        <i class="bi bi-check-circle me-1"></i> Receiving material successfully updated
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>');
		redirect('Warehouse/');
	}

	public function index()
	{
		$data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
		$data['name'] = $this->db->get_where('user', ['name' => $this->session->userdata('name')])->row_array();

		$this->load->model('Warehouse_model');
		$data['receiving_material'] = $this->Warehouse_model->getReceivingMaterials();
		$data['material_list'] = $this->Warehouse_model->getMaterialList();
		// print_r($data['material']);die;
		$data['users'] = $this->Warehouse_model->getAllUsers();

		$data['title'] = 'Receiving Material';
		$this->load->view('templates/header', $data);
		$this->load->view('templates/navbar', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('warehouse/detail_receiving', $data);
		$this->load->view('templates/footer');
	}

	public function edit_box_view($id)
	{
		$data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
		$data['name'] = $this->db->get_where('user', ['name' => $this->session->userdata('name')])->row_array();

		$this->load->model('Warehouse_model');
		$data['box'] = $this->Warehouse_model->getListBoxById($id);
		$data['id_box'] = $id;
		$data['no_box'] = $data['box']->no_box;
		$data['id_sloc'] = $data['box']->sloc;
		$data['detail_box'] = $this->Warehouse_model->getDetailBox($id);
		$data['materials'] = $this->db->query("SELECT * FROM material_list WHERE is_active = 1")->result_array();

		$data['users'] = $this->Warehouse_model->getAllUsers();

		$data['title'] = 'Edit Box';
		$this->load->view('templates/header', $data);
		$this->load->view('templates/navbar', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('warehouse/edit_box', $data);
		$this->load->view('templates/footer');
	}

	public function cycle_box_view($id)
	{
		$data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
		$data['name'] = $this->db->get_where('user', ['name' => $this->session->userdata('name')])->row_array();

		$this->load->model('Warehouse_model');
		$data['box'] = $this->Warehouse_model->getListBoxById($id);
		$data['id_box'] = $id;
		$data['no_box'] = $data['box']->no_box;
		$data['id_sloc'] = $data['box']->sloc;
		$data['detail_box'] = $this->Warehouse_model->getDetailBox($id);
		$data['materials'] = $this->db->query("SELECT * FROM material_list WHERE is_active = 1")->result_array();

		$data['users'] = $this->Warehouse_model->getAllUsers();

		$data['title'] = 'Cycle Count Box';
		$this->load->view('templates/header', $data);
		$this->load->view('templates/navbar', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('warehouse/cycle_edit_box', $data);
		$this->load->view('templates/footer');
	}

	public function get_box_details()
	{
		try {
			// Retrieve the 'id_box' sent via POST
			$id_box = $this->input->post('id_box');

			// Fetch the box details from the database
			$box = $this->Warehouse_model->getListBoxById($id_box);

			// Check if the box exists
			if (!$box) {
				// Return an error response if no box is found
				echo json_encode(['status' => 'error', 'message' => 'Box not found']);
				return;
			}

			// Retrieve the box details from the 'box_detail' table
			$detail = $this->Warehouse_model->getDetailBox($id_box);

			// Calculate total quantity from all detail entries
			$total_qty = 0;
			foreach ($detail as $item) {
				$total_qty += $item['qty']; // Sum up all the 'qty' values
			}

			// Use the 'weight' column for total weight, assuming it's in the 'box' table
			$total_weight = $box->weight;

			// Create the JSON response data
			$data = [
				'status' => 'success',
				'box' => $box,  // Include box object
				'detail' => $detail,  // Include box detail
				'sloc' => $box->sloc_name,  // Include the storage location name
				'total_qty' => $total_qty,  // Send total quantity to the frontend
				'total_weight' => $total_weight  // Send the total weight to the frontend
			];

			// Return the JSON-encoded response
			echo json_encode($data);

		} catch (Exception $e) {
			// Catch any exceptions and return an error message in JSON format
			echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
		}
	}



	public function list_storage()
	{
		$data['title'] = 'List Storage';
		// is_allowed_submenu($data['title']);

		$data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
		$data['name'] = $this->db->get_where('user', ['name' => $this->session->userdata('name')])->row_array();

		$this->load->model('Warehouse_model');
		$data['list_storage'] = $this->Warehouse_model->getListStorage();
		$data['users'] = $this->Warehouse_model->getAllUsers();

		$this->load->view('templates/header', $data);
		$this->load->view('templates/navbar', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('warehouse/list_storage', $data);
		$this->load->view('templates/footer');
	}

	public function show_list_storage()
	{
		$data['title'] = 'Show List Storage';

		$data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
		$data['name'] = $this->db->get_where('user', ['name' => $this->session->userdata('name')])->row_array();

		$this->load->view('templates/header_export', $data);
		$this->load->view('templates/navbar', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('warehouse/show_list_storage', $data);
		$this->load->view('templates/footer_export');
	}

	public function get_data_show_list_storage()
	{
		$this->load->model('Warehouse_model');

		$data = $this->Warehouse_model->getListStorageExport();
		echo json_encode($data);
	}

	public function list_material_report()
	{
		$data['title'] = 'List Material Report';
		// is_allowed_submenu($data['title']);

		$data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
		$data['name'] = $this->db->get_where('user', ['name' => $this->session->userdata('name')])->row_array();

		$this->load->model('Warehouse_model');
		$data['list_storage'] = $this->Warehouse_model->getListStorage();
		$data['users'] = $this->Warehouse_model->getAllUsers();
		$data['materials'] = $this->db->query("SELECT * FROM material_list WHERE is_active = 1")->result_array();

		$this->load->view('templates/header', $data);
		$this->load->view('templates/navbar', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('warehouse/list_material_report', $data);
		$this->load->view('templates/footer');
	}

	public function regrouping()
	{
		$data['title'] = 'Re-Grouping';
		// is_allowed_submenu($data['title']);

		$data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
		$data['name'] = $this->db->get_where('user', ['name' => $this->session->userdata('name')])->row_array();

		$this->load->model('Warehouse_model');
		$data['list_box'] = $this->Warehouse_model->getListBox();
		$data['users'] = $this->Warehouse_model->getAllUsers();

		$this->load->view('templates/header', $data);
		$this->load->view('templates/navbar', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('warehouse/regrouping', $data);
		$this->load->view('templates/footer');
	}

	public function cycle_count()
	{
		$data['title'] = 'Cycle Count';
		// is_allowed_submenu($data['title']);

		$data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
		$data['name'] = $this->db->get_where('user', ['name' => $this->session->userdata('name')])->row_array();

		$this->load->model('Warehouse_model');
		$data['list_box'] = $this->db->query("SELECT id_box, no_box FROM `box`")->result_array();
		$data['users'] = $this->Warehouse_model->getAllUsers();
		$data['materials'] = $this->db->query("SELECT * FROM `material_list` WHERE is_active = 1")->result_array();

		$this->load->view('templates/header', $data);
		$this->load->view('templates/navbar', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('warehouse/cycle_count', $data);
		$this->load->view('templates/footer');
	}

	public function cycle_count_view($id_box)
	{
		$data['box'] = $this->Warehouse_model->getBoxById($id_box);
		$data['detail_box'] = $this->Warehouse_model->getBoxDetails($id_box);
		$data['sloc_availability'] = $this->Warehouse_model->getSLocAvailability(); // Get SLoc availability data

		$this->load->view('warehouse/cycle_count_view', $data);
	}



	public function edit_box()
	{
		$id_box = $this->input->post('id_box');
		$weight = $this->input->post('weight-edit');
		$sloc = $this->input->post('sloc_edit');
		$details = $this->input->post('details');

		$this->Warehouse_model->updateBox($id_box, $weight, $sloc, $details);

		$this->session->set_flashdata('SUCCESS', 'Box updated successfully!');
		redirect('warehouse/manage_box');
	}

	public function add_new_boxes()
	{
		$total_weight = $this->input->post('weight-add');
		$sloc = $this->input->post('sloc_select');
		$box_type = $this->input->post('box_type'); // Retrieve box type
		$number_of_boxes = $this->input->post('number-of-boxes');
		$details = $this->input->post('details') ?? [];

		// Validate details to ensure it's an array
		if (!is_array($details)) {
			$details = [];
		}

		$box_ids = $this->Warehouse_model->addMultipleBoxes($total_weight, $sloc, $box_type, $details, $number_of_boxes);

		if ($box_ids) {
			$this->session->set_flashdata('SUCCESS', 'Boxes added successfully!');
			$this->printMultipleBarcodes($box_ids);
		} else {
			$this->session->set_flashdata('ERROR', 'Failed to add boxes!');
			redirect('warehouse/list_box/0');
		}
	}

	public function add_new_box()
	{
		$total_weight = $this->input->post('weight-add');
		$sloc = $this->input->post('sloc_select');
		$box_type = $this->input->post('box_type'); // Retrieve box type
		$details = $this->input->post('details');

		$box_id = $this->Warehouse_model->addNewBox($total_weight, $sloc, $box_type, $details);

		if ($box_id) {
			$this->session->set_flashdata('SUCCESS', 'Box added successfully!');
		} else {
			$this->session->set_flashdata('ERROR', 'Failed to add box!');
		}

		redirect('warehouse/list_box/0');
	}

	private function printMultipleBarcodes($box_ids)
	{
		$data['box_ids'] = $box_ids;
		$this->load->view('print_barcode', $data);
	}


	public function getLastBoxId()
	{
		$this->load->model('Warehouse_model');
		$lastBox = $this->Warehouse_model->getLastBox();
		echo json_encode($lastBox);
	}



	public function delete_box()
	{
		$id_box = $this->input->post('id_box');
		$result = $this->Warehouse_model->deleteBox($id_box);

		if ($result) {
			echo json_encode(['status' => true]);
		} else {
			echo json_encode(['status' => false, 'msg' => 'Failed to delete the box.']);
		}
	}

	public function deleteBox()
	{
		$id_box = $this->input->post('id_box');

		// Pastikan box ada di database sebelum dihapus
		$this->db->where('id_box', $id_box);
		$delete = $this->db->delete('box'); // Nama tabel box

		if ($delete) {
			$response = [
				'status' => 'success',
				'message' => 'Box deleted successfully'
			];
		} else {
			$response = [
				'status' => 'error',
				'message' => 'Failed to delete box'
			];
		}

		echo json_encode($response);
	}



	public function get_box()
	{
		$id_box = $this->input->post('id_box');
		$box = $this->Warehouse_model->getBoxById($id_box);
		$details = $this->Warehouse_model->getBoxDetails($id_box);
		$all_slocs = $this->Warehouse_model->getAllSlocs(); // Get all SLocs

		if ($box && $details) {
			$data = [
				'status' => true,
				'box' => $box,
				'details' => $details,
				'all_slocs' => $all_slocs // Include all SLocs in the response
			];
		} else {
			$data = [
				'status' => false,
				'msg' => 'Box or details not found'
			];
		}

		echo json_encode($data);
	}

	public function getBoxDetails()
	{
		$slocId = $this->input->post('sloc_id');

		// Query to get the box details for the selected SLoc
		$this->db->select('id_box, no_box');
		$this->db->from('box');
		$this->db->where('sloc', $slocId);

		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			$result = $query->result_array();
			$response = [
				'status' => 'success',
				'boxes' => $result
			];
		} else {
			$response = [
				'status' => 'error',
				'message' => 'No boxes found for this SLoc.'
			];
		}

		echo json_encode($response);
	}

	public function getBoxDetailById()
	{
		$id_box = $this->input->post('id_box');

		// Query untuk mendapatkan detail box
		$this->db->select('box.no_box, box.weight, storage.SLoc as sloc_name, box.created_by, box.created_date');
		$this->db->from('box');
		$this->db->join('storage', 'storage.Id_storage = box.sloc');
		$this->db->where('box.id_box', $id_box);

		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			$box = $query->row_array();

			$response = [
				'status' => 'success',
				'box' => $box
			];
		} else {
			$response = [
				'status' => 'error',
				'message' => 'Box not found'
			];
		}

		echo json_encode($response);
	}



	public function getMaterialByReferenceNumber($reference_number)
	{
		$query = $this->Warehouse_model->getReceivingMaterials("SELECT * FROM material_list WHERE reference_number = '$reference_number'");
		$material_data = $query->row_array();
		return $material_data;
	}

	public function get_material_data()
	{
		$refnumber = $this->input->post('refnumber');
		$refnumber2 = $this->input->post('refnumber2');

		$row = $this->db->query("SELECT Material_desc, Uom FROM material_list WHERE Id_material = '$refnumber' OR Id_material = '$refnumber2'");
		if ($row->row() !== null) {
			$dt = $row->row();
			$data = [
				'status' => true,
				'material' => $dt->Material_desc,
				'uom' => $dt->Uom
			];
		} else {
			$data = [
				'status' => false,
				'msg' => 'No material found for the provided reference number',
			];
		}
		echo json_encode($data);
	}
	public function get_sloc()
	{
		$total_weight = $this->input->post('total_weight');

		// Query untuk mengambil SLoc dan status
		// $this->db->select('s.Id_storage, s.SLoc, s.space_max, IFNULL(SUM(b.weight), 0) as space_now');
		// $this->db->from('storage s');
		// $this->db->join('box b', 'b.sloc = s.Id_storage', 'left');
		// $this->db->group_by('s.Id_storage');
		// $this->db->having('space_now < s.space_max'); // Tampilkan SLoc yang belum penuh

		$result = $this->db->query("SELECT * FROM storage
			WHERE ($total_weight BETWEEN min_loads AND max_loads)
			-- AND space_max > space_now
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
			SLoc ASC;
		")->result_array();


		// $query = $this->db->get();

		// if ($query->num_rows() > 0) {
		// $result = $query->result_array();
		$response = [
			'status' => 'success',
			'sloc' => $result
		];
		// } else {
		// 	$response = [
		// 		'status' => 'empty',
		// 		'message' => 'No available SLocs for the specified total weight'
		// 	];
		// }

		echo json_encode($response);
	}



	private function generateFormattedBoxNumber()
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

	public function save_new_box()
	{
		$total_weight = $this->input->post('total_weight');
		$id_sloc = $this->input->post('id_sloc');
		$material = $this->db->query("SELECT * FROM receiving_material_temp")->result();

		if (empty($material)) {
			$data = [
				'status' => false,
				'msg' => 'Material is empty'
			];
			echo json_encode($data);
			return;
		}

		try {
			$formatted_box_number = $this->generateFormattedBoxNumber();
		} catch (Exception $e) {
			log_message('error', 'Error generating box number: ' . $e->getMessage());
			echo json_encode(['status' => false, 'msg' => 'Error generating box number: ' . $e->getMessage()]);
			return;
		}

		$header = [
			'weight' => $total_weight,
			'no_box' => $formatted_box_number,
			'sloc' => $id_sloc,
			'crtby' => $this->session->userdata('username'),
			'crtdt' => date('Y-m-d H:i:s')
		];

		try {
			$this->Amodel->insertData('box', $header);
		} catch (Exception $e) {
			log_message('error', 'Error inserting box header: ' . $e->getMessage());
			echo json_encode(['status' => false, 'msg' => 'Error inserting box header: ' . $e->getMessage()]);
			return;
		}

		$id_box = $this->db->insert_id();
		$id_box_detail_array = array();

		try {
			foreach ($material as $key => $v) {
				$data = [
					'id_box' => $id_box,
					'id_material' => $v->reference_number,
					'material_desc' => $v->material_desc,
					'crtby' => $this->session->userdata('username'),
					'crtdt' => date('Y-m-d H:i:s')
				];
				$this->Amodel->insertData('box_detail', $data);
				$id_box_detail_array[] = $this->db->insert_id();
			}

			foreach ($material as $key => $v) {
				$id_box_detail = array_shift($id_box_detail_array);

				$data = [
					'id_box' => $id_box,
					'id_box_detail' => $id_box_detail,
					'reference_number' => $v->reference_number,
					'material' => $v->material_desc,
					'qty' => $v->qty,
					'uom' => $v->uom,
					's_loc' => $id_sloc,
					'barcode' => $formatted_box_number,
					'receiving_date' => $v->receiving_date,
					'created_by' => $this->session->userdata('username'),
					'created_at' => date('Y-m-d H:i:s')
				];
				$this->Amodel->insertData('receiving_material', $data);

				$cek_storage = $this->db->query("SELECT total_qty from list_storage where sloc = $id_sloc")->row();
				if (!empty($cek_storage)) {
					$qty = $cek_storage->total_qty + $v->qty;
				} else {
					$qty = $v->qty;
				}
				$list_storage = [
					'id_box' => $id_box,
					'product_id' => $v->reference_number,
					'material_desc' => $v->material_desc,
					'total_qty' => $qty,
					'total_qty_real' => $qty,
					'sloc' => $id_sloc,
					'uom' => $v->uom,
					'created_by' => $this->session->userdata('username'),
					'created_at' => date('Y-m-d H:i:s')
				];
				$this->Amodel->insertData('list_storage', $list_storage);
			}
		} catch (Exception $e) {
			log_message('error', 'Error inserting box details: ' . $e->getMessage());
			echo json_encode(['status' => false, 'msg' => 'Error inserting box details: ' . $e->getMessage()]);
			return;
		}

		$data = [
			'status' => true,
			'no_box' => $formatted_box_number,
		];

		echo json_encode($data);
	}

	public function detail_receiving($box_id)
	{
		$this->load->model('Warehouse_model');
		$data['receiving_material'] = $this->Warehouse_model->get_receiving_by_box_id($box_id);
		$this->load->view('pages/detail_receiving', $data);
	}

	public function delete_receiving_temp()
	{
		$this->db->empty_table('receiving_material_temp');
		$data = [
			'status' => true,
		];

		echo json_encode($data);
	}


	public function list_box()
	{
		$data['title'] = 'List Box';

		$data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
		$data['name'] = $this->db->get_where('user', ['name' => $this->session->userdata('name')])->row_array();


		$config['base_url'] = base_url('warehouse/list_box');
		$config['total_rows'] = $this->Warehouse_model->countAllBoxes();
		$config['per_page'] = 50;

		// Add these two lines for pagination styling (Bootstrap 4)
		$config['full_tag_open'] = '<nav aria-label="Page navigation example"><ul class="pagination">';
		$config['full_tag_close'] = '</ul></nav>';
		$config['first_link'] = '&laquo;';
		$config['first_tag_open'] = '<li class="page-item">';
		$config['first_tag_close'] = '</li>';
		$config['last_link'] = '&raquo;';
		$config['last_tag_open'] = '<li class="page-item">';
		$config['last_tag_close'] = '</li>';
		$config['next_link'] = '&rsaquo;';
		$config['next_tag_open'] = '<li class="page-item">';
		$config['next_tag_close'] = '</li>';
		$config['prev_link'] = '&lsaquo;';
		$config['prev_tag_open'] = '<li class="page-item">';
		$config['prev_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="page-item active"><a class="page-link">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li class="page-item">';
		$config['num_tag_close'] = '</li>';
		$config['attributes'] = array('class' => 'page-link');

		$this->pagination->initialize($config);

		$data['start'] = $this->uri->segment(3, 0);
		$data['list_box'] = $this->Warehouse_model->getBoxes($config['per_page'], $data['start']);

		$this->load->view('templates/header', $data);
		$this->load->view('templates/navbar', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('warehouse/list_box', $data);
		$this->load->view('templates/footer');
	}

	public function get_box_data()
	{
		$data = $this->AModel->getBox();

		echo json_encode($data);
	}


	public function get_detail_box()
	{
		$id = $this->input->post('id');
		$detail_box = $this->Warehouse_model->getDetailBox($id);

		$data = [
			'status' => true,
			'dt' => $detail_box,
		];

		echo json_encode($data);
	}
	public function get_detail_request()
	{
		$Production_plan = $this->input->post('Production_plan');
		$detail_box = $this->Warehouse_model->getProductionRequestDetail($Production_plan);

		$data = [
			'status' => true,
			'dt' => $detail_box,
		];

		echo json_encode($data);
	}

	public function get_detail_storage()
	{
		$id = $this->input->post('id');
		$detail_storage = $this->Warehouse_model->getDetailStorage($id);

		$data = [
			'status' => true,
			'dt' => $detail_storage,
		];

		echo json_encode($data);
	}


	public function get_material_report()
	{
		$id = $this->input->post('id_material');
		$report = $this->Warehouse_model->getMaterialReport($id);
		$qtyEarly = $this->Warehouse_model->qtyMaterial($id);

		$data = [
			'status' => true,
			'dt' => $report,
			'early_qty' => $qtyEarly
		];

		echo json_encode($data);
	}

	public function delete_material_temp()
	{
		$id = $this->input->post('id');
		$this->Warehouse_model->deleteMaterialTemp($id);
		$data = [
			'status' => true,
		];

		echo json_encode($data);
	}

	public function delete_material_box()
	{
		$id = $this->input->post('id');
		$this->Warehouse_model->deleteMaterial($id);
		$data = [
			'status' => true,
		];

		echo json_encode($data);
	}

	public function save_unpack()
	{
		$id_box_detail = $this->input->post('id_box_detail');
		$id_box_destination = $this->input->post('id_box_destination');

		$new_id_Sloc = $this->db->query("SELECT sloc from `box` where id_box = $id_box_destination")->row();

		$dtupdate = [
			'id_box' => $id_box_destination,
			'uptdt' => date('Y-m-d H:i:s'),
			'uptby' => $this->session->userdata('username'),
		];

		$this->db->where('id_box_detail', $id_box_detail);
		$unpack = $this->db->update('box_detail', $dtupdate);

		$dtupdate = [
			'id_box' => $id_box_destination,
			's_loc' => $new_id_Sloc->sloc,
			'updated_at' => date('Y-m-d H:i:s'),
			'updated_by' => $this->session->userdata('username'),
		];
		$this->db->where('id_box_detail', $id_box_detail);
		$unpack = $this->db->update('receiving_material', $dtupdate);

		if ($unpack) {
			$data = [
				'status' => true,
			];
		} else {
			$data = [
				'status' => false,
			];
		}
		echo json_encode($data);
	}

	public function save_cycle_count()
	{
		$id_box_detail = $this->input->post('id_box_detail');
		$qty = $this->input->post('qty');
		$sloc = $this->input->post('sloc');
		$weight = $this->input->post('total_weight');  // Retrieve weight (to update the box table)

		// Update the `receiving_material` table
		$dtupdate = [
			'qty' => $qty,
			's_loc' => $sloc,
			'updated_at' => date('Y-m-d H:i:s'),
			'updated_by' => $this->session->userdata('username'),
		];

		$this->db->where('id_box_detail', $id_box_detail);
		$update_receiving = $this->db->update('receiving_material', $dtupdate);

		// If the update to `receiving_material` is successful, update the `box` table
		if ($update_receiving) {
			$id_box = $this->db->select('id_box')
				->where('id_box_detail', $id_box_detail)
				->get('box_detail')
				->row()
				->id_box;

			// Update the `box` table (only `weight` and `sloc`)
			$box_update = [
				'weight' => $weight,
				'sloc' => $sloc // Update `sloc` as well if needed
			];

			$this->db->where('id_box', $id_box);
			$update_box = $this->db->update('box', $box_update);

			if ($update_box) {
				$data = ['status' => true];
			} else {
				$data = ['status' => false, 'message' => 'Failed to update the box table.'];
			}
		} else {
			$data = ['status' => false, 'message' => 'Failed to update receiving_material table.'];
		}

		echo json_encode($data);
	}



	public function getDataForEdit()
	{
		$id_box_detail = $this->input->post('id_box_detail');
		$detail_box = $this->Warehouse_model->getItemBox($id_box_detail);

		if ($detail_box) {
			$data = [
				'status' => 'success',
				'material' => $detail_box
			];
		} else {
			$data = ['status' => 'failed'];
		}
		echo json_encode($data);
	}

	public function updateTotalWeightAndSloc()
	{
		$id_box = $this->input->post('id_box');
		$total_weight = $this->input->post('total_weight');
		$sloc = $this->input->post('sloc');
		$update = [
			'weight' => $total_weight,
			'sloc' => $sloc
		];
		$this->db->where('id_box', $id_box);
		$update_box = $this->db->update('box', $update);

		if ($update_box) {
			$update = [
				's_loc' => $sloc
			];
			$this->db->where('id_box', $id_box);
			$update_receiving_material = $this->db->update('receiving_material', $update);

			if ($update_receiving_material) {
				$data = [
					'status' => 'success'
				];
			} else {
				$data = [
					'status' => 'failed'
				];
			}
		}
		echo json_encode($data);
	}

	public function kitting()
	{
		$data['title'] = 'Kitting';
		// is_allowed_submenu($data['title']);

		$data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
		$data['name'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();

		$data['boxs'] = $this->PModel->getAllBox();
		$data['requestnoprod'] = $this->WModel->getAllReqNoProd();
		$data['requestnoqual'] = $this->WModel->getAllReqNoQual();

		$this->load->view('templates/header', $data);
		$this->load->view('templates/navbar', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('warehouse/kitting', $data);
		$this->load->view('templates/footer');
	}

	public function kitting_production($reqNo)
	{
		$data['title'] = 'Kitting';

		$data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
		$data['name'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();

		$Box_result = $this->db->query("SELECT 
					DISTINCT pra.id_box, 
					b.no_box
				FROM 
					production_request_approve pra
				LEFT JOIN 
					box b 
					ON pra.id_box = b.id_box
				WHERE 
					pra.Id_request = '$reqNo'
				AND 
					pra.status_kitting = 1")->result_array();

		$Request_result = $this->db->query("SELECT 
					pr.Id_request, 
					pr.Production_plan,
					pp.Id_fg, 
					pp.Fg_desc, 
					pp.Production_plan_qty,
					ppd.Id_material, 
					ppd.Material_desc, 
					ppd.Material_need, 
					ppd.Uom
				FROM 
					production_request pr
				LEFT JOIN 
					production_plan pp 
					ON pr.Production_plan = pp.production_plan
				LEFT JOIN 
					production_plan_detail ppd 
					ON pr.Production_plan = ppd.production_plan
				WHERE 
					ppd.Production_plan = pr.Production_plan
			AND pr.Id_request = '$reqNo'")->result_array();


		$data['Box_result'] = $Box_result;
		$data['Request_result'] = $Request_result;
		$data['reqNo'] = $reqNo;

		$this->load->view('templates/header', $data);
		$this->load->view('templates/navbar', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('warehouse/kitting-production', $data);
		$this->load->view('templates/footer');
	}

	public function kitting_quality($reqNo)
	{
		$data['title'] = 'Kitting';

		$data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
		$data['name'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();

		$result = $this->db->query("SELECT 
					qr.*, 
					qrd.sloc, 
					qrd.id_box, 
					qrd.qty_unpack,
					b.no_box
				FROM 
					quality_request qr
				LEFT JOIN 
					quality_request_detail qrd ON qr.Id_request = qrd.Id_request
				LEFT JOIN 
					box b ON qrd.id_box = b.id_box
				WHERE 
					qr.Id_request = '$reqNo'")->result_array();

		$data['Result'] = $result;
		$data['reqNo'] = $reqNo;

		$this->load->view('templates/header', $data);
		$this->load->view('templates/navbar', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('warehouse/kitting-quality', $data);
		$this->load->view('templates/footer');
	}

	function updateProductionRequestRecord()
	{
		$Data = [
			'status' => 0,
			'Upddt' => date('Y-m-d H:i:s'),
			'Updby' => $this->input->post('user')
		];

		$this->WModel->updatedata('production_request', $Data);
		$check_insert = $this->db->affected_rows();

		if ($check_insert) {
			$this->session->flashdata('success');
		} else {
			$this->session->flashdata('error');
		}

		redirect('warehouse/kitting');
	}

	function getReqNoQR()
	{
		$reqNo = $this->input->post('reqNo');
		$result = $this->db->query("SELECT 
					qr.*, 
					qrd.sloc, 
					qrd.id_box, 
					qrd.qty_unpack,
					b.no_box
				FROM 
					quality_request qr
				LEFT JOIN 
					quality_request_detail qrd ON qr.Id_request = qrd.Id_request
				LEFT JOIN 
					box b ON qrd.id_box = b.id_box
				WHERE 
					qr.Id_request = '$reqNo'")->result_array();
		echo json_encode($result);
	}

	function getBoxP()
	{
		$boxID = $this->input->post('boxID');
		$req_no = $this->input->post('req_no');
		$Box_result = $this->db->query("SELECT 
					b.no_box, 
					b.id_box, 
					b.weight, 
					b.sloc, 
					bd.product_id, 
					bd.material_desc, 
					bd.total_qty, 
					bd.uom,
					s.SLoc
				FROM 
					box b
				LEFT JOIN 
					list_storage bd ON b.id_box = bd.id_box
				LEFT JOIN 
					storage s ON b.sloc = s.Id_storage
				WHERE 
					b.no_box = '$boxID'")->result_array();

		$Quality_result = $this->db->query("SELECT 
				pra.Id_request,
				pra.Sloc,
				pra.id_box, 
				pra.Qty, 
				pra.Production_plan,
				pp.Id_fg, 
				pp.Fg_desc, 
				pp.Production_plan_qty,
				ppd.Id_material, 
				ppd.Material_desc, 
				ppd.Material_need, 
				ppd.Uom
			FROM 
				production_request_approve pra
			LEFT JOIN 
				production_plan pp 
				ON pra.Production_plan = pp.production_plan
			LEFT JOIN 
				production_plan_detail ppd 
				ON pra.Production_plan = ppd.production_plan
			WHERE 
				pra.Id_request = '$req_no'
			AND 
				pra.status_kitting = 1")->result_array();


		$result = [
			'Box_result' => $Box_result,
			'Quality_result' => $Quality_result
		];

		echo json_encode($result);
	}
	public function quality_request()
	{
		$data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
		$data['name'] = $this->db->get_where('user', ['name' => $this->session->userdata('name')])->row_array();
		$data['quality_request'] = $this->WModel->getQualityRequest(); // Ensure this fetches data correctly

		$data['title'] = 'Quality Request';

		$this->load->view('templates/header', $data);
		$this->load->view('templates/navbar', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('warehouse/quality_request', $data);
		$this->load->view('templates/footer');
	}


	function getBox()
	{
		$boxID = $this->input->post('boxID');
		$req_no = $this->input->post('req_no');
		$Box_result = $this->db->query("SELECT 
					b.no_box, 
					b.id_box, 
					b.weight, 
					b.sloc, 
					bd.product_id, 
					bd.material_desc, 
					bd.total_qty, 
					bd.uom,
					s.SLoc
				FROM 
					box b
				LEFT JOIN 
					list_storage bd ON b.id_box = bd.id_box
				LEFT JOIN 
					storage s ON b.sloc = s.Id_storage
				WHERE 
					b.no_box = '$boxID'")->result_array();

		$Quality_result = $this->db->query("SELECT 
					qr.*, 
					qrd.sloc, 
					qrd.id_box, 
					qrd.qty_unpack
				FROM 
					quality_request qr
				LEFT JOIN 
					quality_request_detail qrd ON qr.Id_request = qrd.Id_request
				WHERE 
					qr.Id_request = '$req_no'")->result_array();

		$result = [
			'Box_result' => $Box_result,
			'Quality_result' => $Quality_result
		];

		echo json_encode($result);
	}

	function getBom()
	{
		$product_id = $this->input->post('production_id');
		$boxID = $this->input->post('boxID');

		$resultBox = $this->db->query("SELECT 
					b.no_box, 
					b.weight, 
					b.sloc, 
					bd.product_id, 
					bd.material_desc, 
					bd.total_qty, 
					bd.uom
				FROM box b
				LEFT JOIN list_storage bd ON b.id_box = bd.id_box
				WHERE b.no_box = '$boxID'")->result_array();

		$resultProduct = $this->db->query("SELECT * FROM bom WHERE Id_fg ='$product_id'")->result_array();

		$result = [
			'boxID_result' => $resultBox, // BOX DATA
			'product_result' => $resultProduct // BOM
		];
		echo json_encode($result);
	}

	function save_kitting()
	{
		$checkedItems = $this->input->post('checkedItems');
		$no_box = $this->input->post('fill');
		$reqNo = $this->input->post('reqNo');

		$list_storage = $this->db->query("SELECT * FROM `list_storage` WHERE id_box = '$no_box'")->result_array();

		$check = 0;

		// MENGURANGI JUMLAH UNPACK
		foreach ($list_storage as $storageItem) {
			// Loop through each checked item
			foreach ($checkedItems as $checkedItem) {
				// Check if the product IDs match
				if ($storageItem['product_id'] == $checkedItem['product_id']) {
					// Calculate the new total quantity
					$new_total_qty = $storageItem['total_qty'] - $checkedItem['material_need'];

					// Update the list_storage item in the database
					$this->db->set('total_qty', $new_total_qty)->where('product_id', $storageItem['product_id'])->update('list_storage');
					$check_insert = $this->db->affected_rows();
					if ($check_insert > 0) {

						// RECORD KANBAN BOX LOG
						$query_log = $this->db->last_query();
						$log_data = [
							'affected_table' => 'list_storage',
							'queries' => $query_log,
							'Crtdt' => date('Y-m-d H:i:s'),
							'Crtby' => $this->input->post('user')
						];
						$this->db->insert('kitting_log', $log_data);
						$check += 1;
					}
				}
			}
		}

		if ($check > 0) {
			$this->db->query("UPDATE `production_request_approve` SET status_kitting = 0 WHERE id_box = '$no_box'");

			$box = $this->db->query("SELECT * FROM `box` WHERE id_box = '$no_box'")->result_array();
			$queryPR = $this->db->query("SELECT * FROM `production_request` WHERE Id_request = '$reqNo';")->result_array();
			$Production_plan = $queryPR[0]['Production_plan'];
			$queryPP = $this->db->query("SELECT * FROM `production_plan` WHERE Production_plan = '$Production_plan'")->result_array();
			$Id_fg = $queryPP[0]['Id_fg'];
			$Fg_desc = $queryPP[0]['Fg_desc'];

			$result = [
				'Id_fg' => $Id_fg,
				'Fg_desc' => $Fg_desc,
				'Id_material' => $checkedItems[0]['product_id'],
				'Material_desc' => $checkedItems[0]['material_desc'],
				'Id_request' => $reqNo,
				'no_box' => $box[0]['no_box'],
				'Production_plan' => $Production_plan
			];
		}

		echo json_encode($result);
	}

	function AddKanbanBox()
	{
		$materialID = $this->input->post('materialID');
		$materialDesc = $this->input->post('materialDesc');
		$materialQty = $this->input->post('materialQty');
		$production_plan = $this->input->post('proPlan');
		$ProductID = $this->input->post('id_fg');
		$kanban_id = $this->input->post('id_kanban');


		$Data = array(
			'id_kanban_box' => $kanban_id,
			'Id_material' => $materialID,
			'Material_desc' => $materialDesc,
			'Material_qty' => $materialQty,
			'Product_plan' => $production_plan,
			'product_id' => $ProductID,
			'Crtdt' => date('Y-m-d H:i'),
			'Crtby' => $this->input->post('user'),
			'Upddt' => date('Y-m-d H:i'),
			'Updby' => $this->input->post('user')
		);

		// $this->session->set_flashdata('kanban_data', $Data);
		$Result = $this->PModel->insertData('kanban_box', $Data);

		if ($Result) {
			// Success

			// RECORD KANBAN BOX LOG
			$query_log = $this->db->last_query();
			$log_data = [
				'affected_table' => 'kanban_box',
				'queries' => $query_log,
				'Crtdt' => date('Y-m-d H:i:s'),
				'Crtby' => $this->input->post('user')
			];
			$this->db->insert('kanban_box_log', $log_data);
			$result = 'ADD';
		} else {
			// Failure
			$result = 'ERROR';
		}

		echo json_encode($result);
	}

	public function return_request()
	{
		$data['title'] = 'Return Request';

		$data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
		$data['name'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();

		$data['rwd'] = $this->WModel->getDataReturnRequest();

		$this->load->view('templates/header', $data);
		$this->load->view('templates/navbar', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('warehouse/return_request', $data);
		$this->load->view('templates/footer');
	}

	function approveReturnRequest($id)
	{
		$data['title'] = 'Approve Return Request';

		$data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
		$data['name'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();

		$querybox = $this->WModel->getDataReturnRequestById($id);
		$data['box'] = $querybox[0];
		$box_weight = intval($querybox[0]['box_weight']);
		$data['sloc'] = $this->WModel->getslocbyweight($box_weight);
		$data['id_return'] = $querybox[0]['id_return'];
		$data['return_data_warehouse'] = $this->WModel->getDataReturnRequestById($id);

		$this->load->view('templates/header', $data);
		$this->load->view('templates/navbar', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('warehouse/approveReturnRequest', $data);
		$this->load->view('templates/footer');
	}

	function AddBox()
	{
		$no_box = $this->input->post('no_box');
		$box_type = $this->input->post('box_type');
		$id_return = $this->input->post('id_return');
		$weight = $this->input->post('weight');
		$sloc = $this->input->post('sloc');
		$user = $this->input->post('user');
		$tableData = $this->input->post('tableData');

		// GET SLOC NAME
		$querySloc = $this->db->query("SELECT SLoc FROM `storage` WHERE Id_storage = '$sloc'")->result_array();
		$SLoc = $querySloc[0]['SLoc'];

		// CHECK IF NO BOX EXIST IN BOX TABLE
		$queryBox = $this->db->query("SELECT * FROM `box` WHERE no_box = '$no_box'")->num_rows();
		if ($queryBox > 0) {
			$query = $this->db->query("SELECT * FROM `box` WHERE no_box = '$no_box'")->result_array();
			$id_table_box = $query[0]['id_box'];

			$this->db->query("UPDATE `box` SET sloc = '$sloc' WHERE id_box = '$id_table_box'");

			// INSERT TABLE `box_detail`
			foreach ($tableData as $td) {
				$DataBoxDetail = [
					'id_box' => $id_table_box,
					'id_material' => $td['Id_material'],
					'material_desc' => $td['Material_desc'],
					'crtdt' => date('Y-m-d H:i:s'),
					'crtby' => $user
				];
				$this->PModel->insertData('box_detail', $DataBoxDetail);
			}

			// INSERT TABLE `list_storage`
			foreach ($tableData as $td) {
				$DataListStorage = [
					'product_id' => $td['Id_material'],
					'material_desc' => $td['Material_desc'],
					'sloc' => $sloc,
					'uom' => $td['Material_uom'],
					'total_qty' => $td['Material_qty'],
					'total_qty_real' => $td['Material_qty'],
					'id_box' => $id_table_box,
					'created_by' => $user,
					'created_at' => date('Y-m-d H:i:s')
				];

				$this->PModel->insertData('list_storage', $DataListStorage);
			}
		}
		// IF NO BOX DOESN'T EXIST
		else {

			// INSERT TABLE `box`
			$DataBox = [
				'no_box' => $no_box,
				'weight' => $weight,
				'box_type' => $box_type,
				'sloc' => $sloc,
				'crtby' => $user,
				'crtdt' => date('Y-m-d H:i:s')
			];

			$this->PModel->insertData('box', $DataBox);
			$id_table_box = $this->db->insert_id();

			// INSERT TABLE `box_detail`
			foreach ($tableData as $td) {
				$DataBoxDetail = [
					'id_box' => $id_table_box,
					'id_material' => $td['Id_material'],
					'material_desc' => $td['Material_desc'],
					'crtdt' => date('Y-m-d H:i:s'),
					'crtby' => $user
				];
				$this->PModel->insertData('box_detail', $DataBoxDetail);
			}

			// INSERT TABLE `list_storage`
			foreach ($tableData as $td) {
				$DataListStorage = [
					'product_id' => $td['Id_material'],
					'material_desc' => $td['Material_desc'],
					'sloc' => $sloc,
					'uom' => $td['Material_uom'],
					'total_qty' => $td['Material_qty'],
					'total_qty_real' => $td['Material_qty'],
					'id_box' => $id_table_box,
					'created_by' => $user,
					'created_at' => date('Y-m-d H:i:s')
				];

				$this->PModel->insertData('list_storage', $DataListStorage);
			}
		}

		$result = 3;
		$error = '';

		$this->db->query("UPDATE return_warehouse SET status = 0 WHERE id_return = '$id_return'");

		$res = [
			'result' => $result,
			'error' => $error,
			'no_box' => $no_box,
			'sloc' => $SLoc,
		];

		echo json_encode($res);
	}


	function RejectReturnRequest()
	{
		$id_return = $this->input->post('id_return');
		$reject_description = $this->input->post('reject_description');

		$DataReturnRequest = [
			'status' => 0,
			'reject_description' => htmlspecialchars($reject_description),
			'Upddt' => date('Y-m-d H:i:s'),
			'Updby' => $this->input->post('user')
		];

		// Update the return request in the database
		$this->WModel->updatedataReturn('return_warehouse', $id_return, $DataReturnRequest);
		$check_insert = $this->db->affected_rows();

		// Set flashdata based on the success or failure of the update
		if ($check_insert > 0) {
			$this->session->set_flashdata('SUCCESS_RejectReturnRequest', 'The rejection is successfully');
		} else {
			$this->session->set_flashdata('FAILED_RejectReturnRequest', 'The rejection failed');
		}

		// Redirect to the return_request page
		redirect('warehouse/return_request');
	}

	// Existing function for fetching SLOC based on id_material, leaving this unchanged
	public function get_sloc_options()
	{
		$id_material = $this->input->post('id_material');

		$query = "
        SELECT a.sloc as sloc_id, b.Sloc as sloc_name
        FROM list_storage a
        LEFT JOIN storage b ON b.Id_storage = a.sloc
        WHERE a.product_id = ? AND a.sloc is not null
    ";
		// Execute query with parameter binding
		$data = $this->db->query($query, [$id_material])->result_array();

		echo json_encode($data);
	}
	public function get_all_sloc_options()
	{
		$query = "
			SELECT Id_storage as sloc_id, SLoc as sloc_name
			FROM storage
			WHERE space_now < space_max  -- Ensure sloc has available space
		";
		// Execute query
		$data = $this->db->query($query)->result_array();

		echo json_encode($data);
	}



	public function get_id_box_options()
	{
		$id_material = $this->input->post('id_material');
		$sloc_id = $this->input->post('sloc_id');

		$query = "
			SELECT a.id_box, b.no_box
			FROM list_storage a
			LEFT JOIN box b ON b.id_box = a.id_box
			WHERE a.product_id = ? AND a.sloc = ?
		";

		$data = $this->db->query($query, [$id_material, $sloc_id])->result_array();

		echo json_encode($data);
	}

	public function sloc_availability()
	{
		$data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
		$data['name'] = $this->db->get_where('user', ['name' => $this->session->userdata('name')])->row_array();
		$data['sloc_availability'] = $this->WModel->getSLocAvailability(); // Ensure this fetches data correctly

		$data['title'] = 'SLoc Availability';

		$this->load->view('templates/header', $data);
		$this->load->view('templates/navbar', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('warehouse/sloc_availability', $data);
		$this->load->view('templates/footer');
	}

	public function editItemMaterial()
	{
		// Retrieve the posted data
		$id_box_detail = $this->input->post('id_box_detail');
		$qty = $this->input->post('qty');  // Capture the updated quantity

		// Create the data array to update the material
		$data = array(
			'qty' => $qty,
			'uptdt' => date('Y-m-d H:i:s')  // Optional: update timestamp if needed
		);

		// Perform the update in the database
		$this->db->where('id_box_detail', $id_box_detail);
		$this->db->update('box_detail', $data);

		// Check if the update was successful
		if ($this->db->affected_rows() >= 0) {
			echo json_encode(['status' => 'success']);
		} else {
			echo json_encode(['status' => 'error', 'message' => 'Failed to update material details.']);
		}
	}



	public function edit_box_cc()
	{
		$id_box_detail = $this->input->post('id_box_detail');
		$qty = $this->input->post('qty');

		// Lakukan update material berdasarkan id_box_detail
		$this->db->where('id_box_detail', $id_box_detail);
		$this->db->update('box_detail', ['qty' => $qty]);

		// Kembalikan status sukses atau gagal
		if ($this->db->affected_rows() > 0) {
			echo json_encode(['status' => 'success']);
		} else {
			echo json_encode(['status' => 'error']);
		}
	}

	public function delete_material_cc()
	{
		$id = $this->input->post('id');  // Get the box detail id from POST request
		$this->Warehouse_model->deleteMaterialBox2($id);  // Call the model method for deletion

		// Check if the deletion is successful and return the appropriate response
		if ($this->db->affected_rows() > 0) {
			echo json_encode(['status' => true]);
		} else {
			echo json_encode(['status' => false, 'msg' => 'Failed to delete material.']);
		}
	}

	public function getBoxDetailsBySLoc()
	{
		$sloc = $this->input->post('sloc'); // Get SLoc from the request

		// Fetch all box details for the given SLoc
		$this->db->select('no_box, weight, box_type');
		$this->db->from('box');
		$this->db->where('sloc', $sloc);
		$query = $this->db->get();
		$box_details = $query->result_array();

		if (!empty($box_details)) {
			echo json_encode(['status' => 'success', 'box_details' => $box_details]);
		} else {
			echo json_encode(['status' => 'error', 'message' => 'No boxes found']);
		}
	}


	public function editqty_cc()
	{
		$id_box_detail = $this->input->post('id_box_detail');
		$qty = $this->input->post('qty');
		$sloc = $this->input->post('sloc');
		$weight = $this->input->post('total_weight');  // Retrieve weight (to update the box table)

		// Start transaction
		$this->db->trans_begin();

		// Update the `receiving_material` table
		$dtupdate = [
			'qty' => $qty,
			's_loc' => $sloc,
			'updated_at' => date('Y-m-d H:i:s'),
			'updated_by' => $this->session->userdata('username'),
		];

		$this->db->where('id_box_detail', $id_box_detail);
		$update_receiving = $this->db->update('receiving_material', $dtupdate);

		if ($update_receiving) {
			// Get the associated id_box from `box_detail`
			$id_box = $this->db->select('id_box')
				->where('id_box_detail', $id_box_detail)
				->get('box_detail')
				->row()
				->id_box;

			// Update the `box` table (only `weight` and `sloc`)
			$box_update = [
				'weight' => $weight,
				'sloc' => $sloc
			];

			$this->db->where('id_box', $id_box);
			$update_box = $this->db->update('box', $box_update);

			if ($update_box) {
				// Commit transaction if all updates succeed
				$this->db->trans_commit();
				$data = ['status' => true];
			} else {
				// Log the error and rollback transaction if updating `box` fails
				log_message('error', 'Failed to update the box table for id_box: ' . $id_box);
				$this->db->trans_rollback();
				$data = ['status' => false, 'message' => 'Failed to update the box table.'];
			}
		} else {
			// Log the error and rollback transaction if updating `receiving_material` fails
			log_message('error', 'Failed to update the receiving_material table for id_box_detail: ' . $id_box_detail);
			$this->db->trans_rollback();
			$data = ['status' => false, 'message' => 'Failed to update receiving_material table.'];
		}

		// Send the result as JSON
		echo json_encode($data);
	}
}
?>