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
		$data['title'] = 'Production Request';
		// is_allowed_submenu($data['title']);

		$data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
		$data['name'] = $this->db->get_where('user', ['name' => $this->session->userdata('name')])->row_array();

		$this->load->model('Warehouse_model');
		$data['production_request'] = $this->Warehouse_model->getProductionRequest();
		$data['users'] = $this->Warehouse_model->getAllUsers();

		$this->load->view('templates/header', $data);
		$this->load->view('templates/navbar', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('warehouse/production_request', $data);
		$this->load->view('templates/footer');
	}


	public function rejectProductionRequest()
	{
		$production_plan = $this->input->post('production_plan');

		$data_request = $this->db->query("SELECT * from production_request where Production_plan = '$production_plan'")->result();

		foreach ($data_request as $v) {
			$id_material = $v->Id_material;
			$sloc = $v->Sloc;
			$id_box = $v->id_box;
			$qty = $v->Qty;

			// Update jumlah qty di list_storage
			$this->db->query("UPDATE list_storage SET total_qty_real = total_qty_real + ? WHERE product_id = ? AND sloc = ? AND id_box = ?", array($qty, $id_material, $sloc, $id_box));
		}

		$rejected1 = $this->db->query("UPDATE production_request SET status = 'REJECTED', Sloc = 457 WHERE Production_plan = '$production_plan'");
		$rejected2 = $this->db->query("UPDATE production_plan SET status = 'REJECTED' WHERE Production_plan = '$production_plan'");

		if ($rejected1 && $rejected2) {
			echo json_encode(['status' => true]);
		} else {
			echo json_encode(['status' => false]);
		}

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

	public function addItemBox()
	{
		$id_box = $this->input->post('id_box');
		$uomname = $this->input->post('uom');
		$material = $this->input->post('material');

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

	public function get_box_details()
	{
		$id = $this->input->post('id_box');
		$box = $this->Warehouse_model->getListBoxById($id);
		$id_box = $id;
		$no_box = $box->no_box;
		$id_sloc = $box->sloc;
		$detail = $this->Warehouse_model->getDetailBox($id);

		$data = [
			'status' => 'success',
			'detail' => $detail,
		];
		echo json_encode($data);
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
	
	public function get_data_show_list_storage(){
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
		$data['list_box'] = $this->Warehouse_model->getListBox();
		$data['users'] = $this->Warehouse_model->getAllUsers();

		$this->load->view('templates/header', $data);
		$this->load->view('templates/navbar', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('warehouse/cycle_count', $data);
		$this->load->view('templates/footer');
	}


	public function edit_box()
	{
		$id_box = $this->input->post('id_box');
		$weight = $this->input->post('weight-edit');
		$sloc = $this->input->post('sloc_edit');
		$details = $this->input->post('details');

		$this->Warehouse_model->updateBox($id_box, $weight, $sloc, $details);

		$this->session->set_flashdata('SUCCESS', 'Box updated successfully!');
		redirect('warehouse/list_box/0');
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
		if ($total_weight == '') {
			$data = [
				'status' => 'empty',
				'sloc' => [],
				'msg' => 'No available Slocs for the specified total weight',
			];
			echo json_encode($data);
			die;
		}
		$data = $this->db->query("SELECT * FROM storage WHERE $total_weight BETWEEN min_loads AND max_loads");

		if ($data->result() !== null) {
			$dt = $data->result();
			$data = [
				'status' => 'success',
				'sloc' => $dt
			];
		} else {
			$data = [
				'status' => 'failed',
				'msg' => 'No available Slocs for the specified total weight',
			];
		}
		echo json_encode($data);
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
		$this->load->library('pagination');

		$config['base_url'] = base_url('warehouse/list_box');
		$config['total_rows'] = $this->db->count_all('box');
		$config['per_page'] = 10;
		$config['uri_segment'] = 3;

		$this->pagination->initialize($config);

		$page = ($this->uri->segment(3) !== null) ? (int) $this->uri->segment(3) : 0;
		$data['list_box'] = $this->Warehouse_model->getListBox($config['per_page'], $page);
		$data['pagination'] = $this->pagination->create_links();

		$data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
		$data['name'] = $this->db->get_where('user', ['name' => $this->session->userdata('name')])->row_array();
		$data['title'] = 'List Box';

		$this->load->view('templates/header', $data);
		$this->load->view('templates/navbar', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('warehouse/list_box', $data);
		$this->load->view('templates/footer');
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
		$this->Warehouse_model->deleteMaterialBox($id);
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

		$dtupdate = [
			'qty' => $qty,
			'updated_at' => date('Y-m-d H:i:s'),
			'updated_by' => $this->session->userdata('username'),
		];
		$this->db->where('id_box_detail', $id_box_detail);
		$update = $this->db->update('receiving_material', $dtupdate);

		if ($update) {
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

	public function kitting_production($reqNo){
		$data['title'] = 'Kitting';

		$data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
		$data['name'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();

		$Box_result = $this->db->query("SELECT 
					DISTINCT pr.id_box, 
					b.no_box
				FROM 
					production_request pr
				LEFT JOIN 
					box b 
					ON pr.id_box = b.id_box
				WHERE 
					pr.Id_request = '$reqNo'")->result_array();
		$Request_result = $this->db->query("SELECT 
					pr.Id_request, 
					pr.id_box,
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
	
	public function kitting_quality($reqNo){
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
				pr.Id_request,
				pr.Sloc,
				pr.id_box, 
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
				pr.Id_request = '$req_no'")->result_array();


		$result = [
			'Box_result' => $Box_result,
			'Quality_result' => $Quality_result
		];

		echo json_encode($result);
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

		$list_storage = $this->db->query("SELECT * FROM `list_storage` WHERE id_box = '$no_box'")->result_array();

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
				}
			}
		}

		$box = $this->db->query("SELECT * FROM `box` WHERE id_box = '$no_box'")->result_array();

		$result = $box[0]['no_box'];

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
		$box_type = $this->input->post('box_type');
		$id_return = $this->input->post('id_return');
		$weight = $this->input->post('weight');
		$sloc = $this->input->post('sloc');
		$user = $this->input->post('user');
		$tableData = $this->input->post('tableData');
		$no_box = $this->WModel->getLastNoBox();

		$DataBox = [
			'no_box' => $no_box,
			'weight' => $weight,
			'sloc' => $sloc,
			'box_type' => $box_type,
			'crtby' => $user,
			'crtdt' => date('Y-m-d H:i:s'),
		];
		$this->PModel->insertData('box', $DataBox);
		$insert_box = $this->db->affected_rows();

		// JIKA BERHASIL INSERT TABLE BOX
		if ($insert_box > 0) {

			// GET DATA FOR ID BOX
			$Box = $this->db->query("SELECT * FROM box WHERE no_box = '$no_box'")->result_array();
			$id_box = intval($Box[0]['id_box']);

			foreach ($tableData as $td) {
				$insert_box_detail = 0;
				$DataBoxDetail = [
					'id_box' => $id_box,
					'id_material' => $td['Id_material'],
					'material_desc' => $td['Material_desc'],
					'crtdt' => date('Y-m-d H:i:s'),
					'crtby' => $user
				];
				$this->PModel->insertData('box_detail', $DataBoxDetail);
				$check_insert_box_detail = $this->db->affected_rows();
				if ($check_insert_box_detail > 0) {
					$insert_box_detail += 1;
				}
			}

			// JIKA BERHASIL INSERT TABLE BOX DETAIL
			if ($insert_box_detail > 0) {
				$insert_list_storage = 0;
				foreach ($tableData as $td) {
					$DataListStorage = [
						'product_id' => $td['Id_material'],
						'material_desc' => $td['Material_desc'],
						'sloc' => $sloc,
						'uom' => $td['Material_uom'],
						'total_qty' => $td['Material_qty'],
						'total_qty_real' => $td['Material_qty'],
						'id_box' => $id_box,
						'created_by' => $user,
						'created_at' => date('Y-m-d H:i:s')
					];

					$this->PModel->insertData('list_storage', $DataListStorage);
					$check_insert_list_storage = $this->db->affected_rows();
					if ($check_insert_list_storage > 0) {
						$insert_list_storage += 1;
					}
				}

				// JIKA BERHASIL INSERT TABLE LIST STORAGE
				if ($insert_list_storage > 0) {
					$result = 3;
					$error = '';

					$this->db->query("UPDATE return_warehouse SET status = 0 WHERE id_return = '$id_return'");
				}
				// JIKA GAGAL INSERT TABLE LIST STORAGE
				else {
					$result = 1;
					$error = 'Failed insert data box';
				}
			}
			// JIKA GAGAL INSERT TABLE BOX DETAIL
			else {
				$result = 2;
				$error = 'Failed insert data box';
			}
		}
		// JIKA GAGAL INSERT TABLE BOX
		else {
			$result = 0;
			$error = 'Failed insert data box';
		}

		// $result = 3;
		// $error = '';
		// $no_box = 'CKA0000001';

		$res = [
			'result' => $result,
			'error' => $error,
			'box_id' => $no_box,
		];

		echo json_encode($res);
	}

	public function approveProductionRequest()
	{
		$production_plan = $this->input->post('production_plan');
		$data_update = $this->input->post('data_items');

		foreach ($data_update as $v) {
			$id_material = $v['Id_material'];
			$sloc = $v['sloc_id'];
			$id_box = $v['id_box'];


			// Update sloc and box production_request
			$this->db->query("UPDATE production_request SET Sloc = ?, id_box = ? WHERE id_material = ? AND Production_plan = ? ", array($sloc, $id_box, $id_material, $production_plan));
		}

		$data_request = $this->db->query("SELECT * from production_request where Production_plan = '$production_plan'")->result();
		foreach ($data_request as $v) {
			$id_material = $v->Id_material;
			$sloc = $v->Sloc;
			$id_box = $v->id_box;
			$qty = $v->Qty;

			// Update jumlah qty di list_storage
			$this->db->query("UPDATE list_storage SET total_qty = total_qty - ? WHERE product_id = ? AND sloc = ? AND id_box = ?", array($qty, $id_material, $sloc, $id_box));
		}

		$approved1 = $this->db->query("UPDATE production_request SET status = 'APPROVED' WHERE Production_plan = '$production_plan'");
		$approved2 = $this->db->query("UPDATE production_plan SET status = 'APPROVED' WHERE Production_plan = '$production_plan'");

		if ($approved1 && $approved2) {
			echo json_encode(['status' => true]);
		} else {
			echo json_encode(['status' => false]);
		}
	}
	
	function RejectReturnRequest()
	{
		$id_return = $this->input->post('idReturn');

		$DataReturnRequest = [
			'status' => 0,
			'Upddt' => date('Y-m-d H:i:s'),
			'Updby' => $this->input->post('user')
		];

		$this->WModel->updatedataReturn('return_warehouse', $id_return, $DataReturnRequest);
		$check_insert = $this->db->affected_rows();

		if ($check_insert > 0) {
			$result = 1;
		} else {
			$result = 0;
		}

		echo json_encode($result);
	}

	public function get_sloc_options() {
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
	
	public function get_id_box_options() {
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
}
?>