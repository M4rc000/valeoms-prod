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
		$this->load->model('Admin_model', 'Amodel');
		
        // require_once(APPPATH . 'vendor/phpqrcode/qrlib.php');
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

		// Masukkan data ke dalam tabel receiving_material
		$this->Amodel->insertData('receiving_material_temp', $data);

		// Set flash data untuk menampilkan pesan sukses
		$this->session->set_flashdata('SUCCESS', '<div class="alert alert-success alert-dismissible fade show mb-2" id="dismiss" role="alert" style="width: 40%">
			<i class="bi bi-check-circle me-1"></i> New receiving material successfully added
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>');

		// Redirect ke halaman yang sesuai
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
		// Fetch user data
		$data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
		$data['name'] = $this->db->get_where('user', ['name' => $this->session->userdata('name')])->row_array();

		// Fetch receiving materials from the model
		$this->load->model('Warehouse_model');
		$data['receiving_material'] = $this->Warehouse_model->getReceivingMaterials();

		// Fetch all users
		$data['users'] = $this->Warehouse_model->getAllUsers();

		// // Load views
		// $data['title'] = 'Receiving Material';
		// $this->load->view('templates/header', $data);
		// $this->load->view('templates/navbar', $data);
		// $this->load->view('templates/sidebar', $data);
		// $this->load->view('warehouse/receiving_material', $data);
		// $this->load->view('templates/footer');
		// Fetch receiving materials from the model
		$this->load->model('Warehouse_model');
		$data['list_storage'] = $this->Warehouse_model->getReceivingMaterials();

		// Fetch all users
		$data['users'] = $this->Warehouse_model->getAllUsers();

		// Load views
		$data['title'] = 'Receiving Material';
		$this->load->view('templates/header', $data);
		$this->load->view('templates/navbar', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('warehouse/detail_receiving', $data);
		// $this->load->view('warehouse/receiving_material', $data);
		$this->load->view('templates/footer');
	}


	public function list_storage()
	{
		// Fetch user data
		$data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
		$data['name'] = $this->db->get_where('user', ['name' => $this->session->userdata('name')])->row_array();

		// Fetch receiving materials from the model
		$this->load->model('Warehouse_model');
		$data['list_storage'] = $this->Warehouse_model->getListStorage();

		// Fetch all users
		$data['users'] = $this->Warehouse_model->getAllUsers();

		// Load views
		$data['title'] = 'List Storage';
		$this->load->view('templates/header', $data);
		$this->load->view('templates/navbar', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('warehouse/list_storage', $data);
		$this->load->view('templates/footer');
	}

	public function list_material_report()
	{
		// Fetch user data
		$data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
		$data['name'] = $this->db->get_where('user', ['name' => $this->session->userdata('name')])->row_array();

		// Fetch receiving materials from the model
		$this->load->model('Warehouse_model');
		$data['list_storage'] = $this->Warehouse_model->getListStorage();

		// Fetch all users
		$data['users'] = $this->Warehouse_model->getAllUsers();

		// Load views
		$data['title'] = 'List Material Report';
		$this->load->view('templates/header', $data);
		$this->load->view('templates/navbar', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('warehouse/list_material_report', $data);
		$this->load->view('templates/footer');
	}

	public function list_box()
	{
		// Fetch user data
		$data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
		$data['name'] = $this->db->get_where('user', ['name' => $this->session->userdata('name')])->row_array();

		// Fetch receiving materials from the model
		$this->load->model('Warehouse_model');
		$data['list_box'] = $this->Warehouse_model->getListBox();

		// Fetch all users
		$data['users'] = $this->Warehouse_model->getAllUsers();

		// Load views
		$data['title'] = 'List Box';
		$this->load->view('templates/header', $data);
		$this->load->view('templates/navbar', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('warehouse/list_box', $data);
		$this->load->view('templates/footer');
	}

	// Fungsi untuk mengambil data material berdasarkan reference number
	public function getMaterialByReferenceNumber($reference_number)
	{
		// Query ke database untuk mengambil data material dari material_list berdasarkan reference number
		// Gantilah bagian ini sesuai dengan struktur tabel dan logika pengambilan data di backend Anda
		$query = $this->Warehouse_model->getReceivingMaterials("SELECT * FROM material_list WHERE reference_number = '$reference_number'");
		$material_data = $query->row_array();
		return $material_data;
	}

	public function get_material_data()
	{
		$refnumber = $this->input->post('refnumber');
		$refnumber2 = $this->input->post('refnumber2');

		$row = $this->db->query("SELECT Material_desc, Uom FROM material_list WHERE Id_material = '$refnumber' or Id_material = '$refnumber2'");
		// print_r($row);die;
		if($row->row() !== null){
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

	public function get_sloc(){
		$total_weight = $this->input->post('total_weight');
		if($total_weight == ''){
			$data = [
				'status' => 'empty',
				'sloc' => [],
				'msg' => 'No available Slocs for the specified total weight',
			];
			echo json_encode($data); die;
		}
		$data =  $this->db->query("SELECT * FROM storage WHERE max_loads >= $total_weight");
		if($data->result() !== null){
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
	public function save_new_box(){
		$total_weight = $this->input->post('total_weight');
		$id_sloc = $this->input->post('id_sloc');
		$material = $this->db->query("SELECT * FROM receiving_material_temp")->result();

		if(empty($material)) {
			$data = [
				'status' => false,
				'msg' => 'Material is empty'
			];

			echo json_encode(($data));die;
		}

		//save header box
		$header = [
			'weight' => $total_weight,
			'no_box' => 'B/'. date('Y-m', time()) . '/' . '04',
			'sloc' => $id_sloc, 
			'crtby' => $this->session->userdata('username'),
			'crtdt' => date('Y-m-d H:i:s')
		];
		$this->Amodel->insertData('box', $header);
		$id_box = $this->db->insert_id();

		//save detail box
		foreach($material as $key => $v){
			$data = [
				'id_box' => $id_box,
				'id_material' => $v->reference_number, 
				'material_desc' => $v->material_desc, 
				'crtby' => $this->session->userdata('username'),
				'crtdt' => date('Y-m-d H:i:s')
			];
			$this->Amodel->insertData('box_detail', $data);
		}
		
		//save receiving material utama
		$material = $this->db->query("SELECT * FROM receiving_material_temp")->result();
		foreach($material as $key => $v){
			$data = [
				'id_box' => $id_box,
				'reference_number' => $v->reference_number,
				'material' => $v->material_desc, 
				'qty' => $v->qty,
				'uom' => $v->uom, 
				's_loc' => $id_sloc, 
				'barcode' => $header['no_box'], 
				'receiving_date' => $v->receiving_date,
				'created_by' => $this->session->userdata('username'),
				'created_at' => date('Y-m-d H:i:s')
			];
			$this->Amodel->insertData('receiving_material', $data);

			//save list storage
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

		$data = [
			'status' => true,
			'no_box' => $header['no_box'],
		];

		echo json_encode(($data));
		
	}
	
	public function detail_receiving($box_id)
	{
		// Load model
		$this->load->model('Warehouse_model');

		// Panggil fungsi dari model untuk mengambil data
		$data['receiving_material'] = $this->Warehouse_model->get_receiving_by_box_id($box_id);

		// Load view untuk halaman detail
		$this->load->view('pages/detail_receiving', $data);
	}

	public function delete_receiving_temp(){
		$this->db->empty_table('receiving_material_temp');
		$data = [
			'status' => true,
		];

		echo json_encode(($data));
	}

	public function get_detail_box(){
		$id = $this->input->post('id');
		$detail_box = $this->Warehouse_model->getDetailBox($id);
		
		$data = [
			'status' => true,
			'dt' => $detail_box,
		];

		echo json_encode(($data));
	}

	public function get_detail_storage(){
		$id = $this->input->post('id');
		$detail_storage = $this->Warehouse_model->getDetailStorage($id);
		
		$data = [
			'status' => true,
			'dt' => $detail_storage,
		];

		echo json_encode(($data));
	}

	public function get_material_report(){
		$id = $this->input->post('id_material');
		$report = $this->Warehouse_model->getMaterialReport($id);
		$qtyEarly = $this->Warehouse_model->qtyMaterial($id);

		$data = [
			'status' => true,
			'dt' => $report,
			'early_qty' => $qtyEarly
		];


		echo json_encode(($data));


	}

	public function delete_material_temp(){
		$id = $this->input->post('id');
		$this->Warehouse_model->deleteMaterialTemp($id);
		$data = [
			'status' => true,
			
		];

		echo json_encode(($data));
	}
}