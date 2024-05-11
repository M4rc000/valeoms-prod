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
    }

    public function AddReceivingMaterial()
    {
        // Ambil reference number dari input pengguna
        $reference_number = $this->input->post('reference_number');

        $this->load->model('Warehouse_model');
        $this->load->model('Admin_model', 'Amodel');
        // Panggil fungsi di model untuk mengambil data material berdasarkan reference number
        // $material_data = $this->Warehouse_model->getReceivingMaterials($reference_number);
        // Buat array data yang akan disimpan ke dalam tabel receiving_material
        $uomname = $this->input->post('uom');
        $material = $this->input->post('material');
        $data = array(
            'reference_number' => $reference_number,
            'material' => (empty($material) ? '' : $material), // Ambil material_desc dari data material
            'qty' => $this->input->post('qty'),
            'uom' => (empty($uomname) ? '' : $uomname), // Ambil Uom dari data material
            's_loc' => $this->input->post('s_loc'),
            'barcode' => $this->input->post('barcode'),
        );

        // Masukkan data ke dalam tabel receiving_material
        $this->Amodel->insertData('receiving_material', $data);

        // Set flash data untuk menampilkan pesan sukses
        $this->session->set_flashdata('SUCCESS', '<div class="alert alert-success alert-dismissible fade show mb-2" id="dismiss" role="alert" style="width: 40%">
			<i class="bi bi-check-circle me-1"></i> New receiving material successfully added
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>');

        // Redirect ke halaman yang sesuai
        redirect('Warehouse/');
    }


    public function editReceivingMaterial()
    {
        $id = $this->input->post('id');
        $data = array(
            'id_box' => $this->input->post('id_box'),
            'material' => $this->input->post('material'),
            'qty' => $this->input->post('qty'),
            'uom' => $this->input->post('uom'),
            'weight_total' => $this->input->post('weight'),
            'size' => $this->input->post('size'),
            's_loc' => $this->input->post('s_loc'),
            'barcode' => $this->input->post('barcode'),
        );

        $this->AModel->updateData('receiving_material', $id, $data);
        $this->session->set_flashdata('SUCCESS', '<div class="alert alert-success alert-dismissible fade show mb-2" id="dismiss" role="alert" style="width: 40%">
        <i class="bi bi-check-circle me-1"></i> Receiving material successfully updated
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>');
        redirect('admin/receiving_material');
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

        // Load views
        $data['title'] = 'Receiving Material';
        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('warehouse/receiving_material', $data);
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

    function get_material_data()
    {
        $refnumber = $this->input->post('refnumber');
        $row = $this->db->query("SELECT Material_desc, Uom FROM material_list WHERE Id_material = '$refnumber'")->row();
        $data = [
            'material' => $row->Material_desc,
            'uom' => $row->Uom
        ];
        echo json_encode($data);
    }
}
