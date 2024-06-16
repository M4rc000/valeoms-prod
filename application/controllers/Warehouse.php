<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Warehouse extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		is_logged_in();
		$this->load->library('form_validation');
		$this->load->model('Warehouse_model');
		$this->load->model('Warehouse_model', 'WModel');
		$this->load->model('Admin_model', 'Amodel');
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
		$data['users'] = $this->Warehouse_model->getAllUsers();

		$data['title'] = 'Receiving Material';
		$this->load->view('templates/header', $data);
		$this->load->view('templates/navbar', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('warehouse/detail_receiving', $data);
		$this->load->view('templates/footer');
	}

	public function list_storage()
	{
		$data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
		$data['name'] = $this->db->get_where('user', ['name' => $this->session->userdata('name')])->row_array();

		$this->load->model('Warehouse_model');
		$data['list_storage'] = $this->Warehouse_model->getListStorage();
		$data['users'] = $this->Warehouse_model->getAllUsers();

		$data['title'] = 'List Storage';
		$this->load->view('templates/header', $data);
		$this->load->view('templates/navbar', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('warehouse/list_storage', $data);
		$this->load->view('templates/footer');
	}

	public function list_material_report()
	{
		$data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
		$data['name'] = $this->db->get_where('user', ['name' => $this->session->userdata('name')])->row_array();

		$this->load->model('Warehouse_model');
		$data['list_storage'] = $this->Warehouse_model->getListStorage();
		$data['users'] = $this->Warehouse_model->getAllUsers();

		$data['title'] = 'List Material Report';
		$this->load->view('templates/header', $data);
		$this->load->view('templates/navbar', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('warehouse/list_material_report', $data);
		$this->load->view('templates/footer');
	}

	public function regrouping()
	{
		$data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
		$data['name'] = $this->db->get_where('user', ['name' => $this->session->userdata('name')])->row_array();

		$this->load->model('Warehouse_model');
		$data['list_box'] = $this->Warehouse_model->getListBox();
		$data['users'] = $this->Warehouse_model->getAllUsers();

		$data['title'] = 'Re-Grouping';
		$this->load->view('templates/header', $data);
		$this->load->view('templates/navbar', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('warehouse/regrouping', $data);
		$this->load->view('templates/footer');
	}

	public function cycle_count()
	{
		$data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
		$data['name'] = $this->db->get_where('user', ['name' => $this->session->userdata('name')])->row_array();

		$this->load->model('Warehouse_model');
		$data['list_box'] = $this->Warehouse_model->getListBox();
		$data['users'] = $this->Warehouse_model->getAllUsers();

		$data['title'] = 'Cycle Count';
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
		redirect('warehouse/list_box');
	}


	public function add_new_boxes()
	{
		$total_weight = $this->input->post('weight-add');
		$sloc = $this->input->post('sloc_select');
		$number_of_boxes = $this->input->post('number-of-boxes');
		$details = $this->input->post('details') ?? [];

		// Validate details to ensure it's an array
		if (!is_array($details)) {
			$details = [];
		}

		$box_ids = $this->Warehouse_model->addMultipleBoxes($total_weight, $sloc, $details, $number_of_boxes);

		if ($box_ids) {
			$this->session->set_flashdata('SUCCESS', 'Boxes added successfully!');
			$this->printMultipleBarcodes($box_ids);
		} else {
			$this->session->set_flashdata('ERROR', 'Failed to add boxes!');
			redirect('warehouse/list_box');
		}
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

	public function add_new_box()
	{
		$total_weight = $this->input->post('weight-add');
		$sloc = $this->input->post('sloc_select');
		$details = $this->input->post('details');

		$box_id = $this->Warehouse_model->addNewBox($total_weight, $sloc, $details);

		if ($box_id) {
			$this->session->set_flashdata('SUCCESS', 'Box added successfully!');
		} else {
			$this->session->set_flashdata('ERROR', 'Failed to add box!');
		}

		redirect('warehouse/list_box');
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

				$list_storage = [
					'id_box' => $id_box,
					'product_id' => $v->reference_number,
					'material_desc' => $v->material_desc,
					'total_qty' => $v->qty,
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
		$data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
		$data['name'] = $this->db->get_where('user', ['name' => $this->session->userdata('name')])->row_array();

		// Fetch receiving materials from the model
		$data['list_box'] = $this->Warehouse_model->getListBox();
		$data['users'] = $this->Warehouse_model->getAllUsers();

		// Define the title variable
		$data['title'] = 'List Box';

		// Load views
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


	public function kitting(){
        $data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $data['name'] = $this->db->get_where('user', ['name' => $this->session->userdata('name')])->row_array();
        
        $data['title'] = 'Kitting';
        $data['boxs'] = $this->PModel->getAllBox();
		$data['requestno'] = $this->WModel->getAllReqNo();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);   
        $this->load->view('templates/sidebar', $data);   
        $this->load->view('warehouse/kitting', $data);
        $this->load->view('templates/footer');
    }

	function getReqNo()
    {
        $reqNo = $this->input->post('reqNo');
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
        
		$resultReq = $this->db->query("SELECT * FROM `material_request` WHERE Id_request = '$reqNo'")->result_array();

		$productionPlan = $resultReq[0]['Production_plan'];
    	$resultProdPlan = $this->db->query("SELECT
				pp.Production_plan,
				pp.Id_fg,
				pp.Fg_desc,
				pp.Production_plan_qty,
				ppd.Id_material,
				ppd.Material_desc,
				ppd.Material_need,
				ppd.Uom
			FROM
				production_plan pp
			JOIN
				production_plan_detail ppd ON pp.Production_plan = ppd.Production_plan
			WHERE
				ppd.status = 1
				AND pp.Production_plan = '$productionPlan'")->result_array();

        $result = [
            'boxID_result' => $resultBox,
            'reqNo_result' => $resultReq,
			'ProdPlan_result' => $resultProdPlan
        ];

        echo json_encode($result);
    }

	function getBox()
    {
        $boxID = $this->input->post('boxID');
        $result = $this->db->query("SELECT 
                b.no_box, 
                b.weight, 
                b.sloc, 
                bd.product_id, 
                bd.material_desc, 
                bd.total_qty, 
                bd.uom
            FROM box b
            LEFT JOIN list_storage bd ON b.id_box = bd.id_box
            WHERE b.no_box = '$boxID'
        ")->result_array();
       
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
}
?>