<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Master extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->library('form_validation');
        $this->load->model('Master_model', 'MModel');
        $this->load->library('pagination');
    }
	
	public function index()
    {
        $data['title'] = 'Material List';

        $data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $data['name'] = $this->db->get_where('user', ['name' => $this->session->userdata('name')])->row_array();

        $data['material_list'] = $this->MModel->getMaterials();
        $data['total_rows'] = $this->MModel->countAllMaterials();

        $config['base_url'] = base_url('master/index');
        $config['total_rows'] = $this->MModel->countAllMaterials();
        $config['per_page'] = 50;

        $this->pagination->initialize($config);

        $data['start'] = $this->uri->segment(3) ? $this->uri->segment(3) : 0;
        $data['materials'] = $this->MModel->getAllMaterials($config['per_page'], $data['start']);

        $data['total_pages'] = ceil($config['total_rows'] / $config['per_page']);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');   
        $this->load->view('templates/sidebar');   
        $this->load->view('master/material_list', $data);
        $this->load->view('templates/footer');
    }

        // CREATE MATERIAL LIST
        public function AddMaterialList(){
            $Id_material = $this->input->post('material_id');
            $Material_desc = $this->input->post('material_desc');
            $Data = array(
                'Id_material' => $this->input->post('material_id'),
                'Material_desc' => $this->input->post('material_desc'),
                'Material_type' => $this->input->post('material_type'),
                'Uom' => $this->input->post('uom'),
                'Family' => $this->input->post('family'),
                'is_active' => 1,
                'Crtdt' => date('Y-d-m H:i'),
                'Crtby' => $this->input->post('user'),
                'Upddt' => date('Y-d-m H:i'),
                'Updby' => $this->input->post('user')
            );

            // CHECK DUPLICATE
            $query = "SELECT * FROM `material_list` WHERE Id_material = ? OR Material_desc = ?";
            $CheckDuplicate = $this->db->query($query, array($Id_material, $Material_desc))->num_rows();

            if ($CheckDuplicate > 0) {
                // MATERIAL'S DUPLICATE
                $this->session->set_flashdata('DUPLICATE_AddMaterialList','Material\'s already exist');
                redirect('master/');
            } else {
                $this->MModel->insertData('material_list', $Data);
                $check_insert = $this->db->affected_rows();
                
                if($check_insert > 0){
                    $this->session->set_flashdata('SUCCESS_AddMaterialList','New Material has successfully added');
                }
                else{
                    $this->session->set_flashdata('FAILED_AddMaterialList','Failed to add a new material');
                }
                redirect('master/');
            }
        }

        // READ MATERIAL LIST
        public function getMaterialList(){
            $id_material = htmlspecialchars($this->input->post('Id_material'));
            $result = $this->db->query("SELECT Id, Id_material, Material_desc, Material_type, Uom, Family FROM material_list WHERE Id_material = '$id_material'")->result_array();
        
            echo json_encode($result);
        }

        // UPDATE MATERIAL LIST
        public function EditMaterialList(){
            $id = $this->input->post('id');
            
            $Data = array(
                'Id_material' => $this->input->post('material_id'),
                'Material_desc' => $this->input->post('material_desc'),
                'Material_type' => $this->input->post('material_type'),
                'Uom' => $this->input->post('uom'),
                'Family' => $this->input->post('family'),
                'Upddt' => date('Y-d-m H:i'),
                'Updby' => $this->input->post('user')
            );

            $this->MModel->updateData('material_list', $id, $Data);
            $check_insert = $this->db->affected_rows();

            if($check_insert > 0 ){
                $this->session->set_flashdata('SUCCESS_EditMaterialList','Material has successfully updated');
            }
            else{
                $this->session->set_flashdata('FAILED_EditMaterialList','Failed to update a material');
            }
            
            redirect('master/');
        }

        // DELETE MATERIAL LIST
        public function DeleteMaterialID(){
            $id = $this->input->post('id');
            $user = $this->input->post('user');
            $this->db->query("UPDATE `material_list` SET is_active = 0 WHERE Id = '$id' AND Updby = '$user'");
            $check_delete = $this->db->affected_rows();

            if($check_delete > 0){
                $this->session->set_flashdata('SUCCESS_DeleteMaterialID','Material has successfully deleted');
            }
            else{
                $this->session->set_flashdata('FAILED_DeleteMaterialID','Failed to delete a Material');
            }
            redirect('master/');  
        }

	public function bom()
	{
        $data['title'] = 'BOM';

        $data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $data['name'] = $this->db->get_where('user', ['name' => $this->session->userdata('name')])->row_array();

        $data['bom'] = $this->MModel->getBom();
        $data['bom_distint'] = $this->MModel->getBomDistinct();
        $data['materials'] = $this->MModel->getListMaterial();
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');   
        $this->load->view('templates/sidebar');   
        $this->load->view('master/bom', $data);
        $this->load->view('templates/footer');
	}

    // ADD BOM MATERIAL
        // SEARCH PRODUCT DESCRIPTION AUTOMATICALLY
        function getProductDesc(){
            $productId = htmlspecialchars($this->input->post('productId'));
            $result = $this->db->query("SELECT Fg_desc FROM bom WHERE Id_fg = '$productId' AND is_active = 1")->result_array();
        
            echo json_encode($result);
        }
        
        function getMaterialDesc(){
            $materialID = htmlspecialchars($this->input->post('materialID'));
            $result = $this->db->query("SELECT Material_desc, Material_type, Uom FROM material_list WHERE Id_material = '$materialID' AND is_active = 1")->result_array();
        
            echo json_encode($result);
        }
    
    function AddMaterialBom(){
        $Data = array(
            'Id_fg' => $this->input->post('product_id'),
            'Fg_desc' => $this->input->post('fg_desc'),
            'is_active' => 1,
            'Id_material' => $this->input->post('material_id'),
            'Material_desc' => $this->input->post('material_desc'),
            'Material_type' => $this->input->post('material_type'),
            'Uom' => $this->input->post('uom'),
            'Qty' => floatval($this->input->post('qty')),
            'Crtdt' => date('Y-d-m H:i'),
            'Crtby' => $this->input->post('user'),
            'Upddt' => date('Y-d-m H:i'),
            'Updby' => $this->input->post('user')
        );

        $this->MModel->insertData('bom', $Data);
        $check_insert = $this->db->affected_rows();

        if($check_insert > 0){
            $this->session->set_flashdata('success_AddMaterialBom', 'Material Bom has been successfully added');
        }
        else{
            $this->session->set_flashdata('failed_AddMaterialBom', 'Failed to add Material Bom');
        }

        redirect('master/bom');
    }

    // ADD NEW BOM
    function addNewBom(){
        $materials = $this->input->post('materials');
        $id_fg = $this->input->post('products_id');

        // CHECK IF BOM ALREADY EXIST
        $Bom = $this->db->query("SELECT Id_fg FROM BOM WHERE Id_fg = '$id_fg' and is_active = 1")->result_array();
        $check_bom = count($Bom);
        if($check_bom > 0){
            $this->session->set_flashdata('duplicate_add_new_bom', $id_fg);
            redirect('master/bom');
        }

        $insert_bom = 0;
        foreach ($materials as $material) {
            $DataBOM = [
                'Id_material' => $material['material_id'],
                'Material_desc' => $material['material_desc'],
                'Material_type' => $material['material_type'],
                'Qty' => floatval($material['qty']),
                'Uom' => $material['uom'],
                'Id_fg' => $this->input->post('products_id'),
                'Fg_desc' => $this->input->post('product_desc'),
                'is_active' => 1,
                'Crtdt' => date('Y-d-m H:i:s'),
                'Crtby' => $this->input->post('user'),
                'Upddt' => date('Y-d-m H:i:s'),
                'Updby' => $this->input->post('user')
            ];
       
            $this->MModel->insertData('bom', $DataBOM);
            $check_insert = $this->db->affected_rows();
            if($check_insert > 0){
                $insert_bom += 1;
            }
        }

        if($insert_bom > 0){
            $this->session->set_flashdata('success_add_new_bom', $id_fg);
        }
        else{
            $this->session->set_flashdata('failed_add_new_bom', $id_fg);
        }

        redirect('master/bom');
    }

    
    // READ BOM
    function getBomList(){
        $Id_product = htmlspecialchars($this->input->post('Id_product'));
        if($Id_product){
            $result = $this->db->query("SELECT * FROM bom WHERE Id_fg = '$Id_product' AND is_active = 1")->result_array();
        }
        else{
            $result = $this->db->query("SELECT * FROM bom WHERE is_active = 1")->result_array();
        }
       
        echo json_encode($result);
    }    
    
    function getAllBom(){
        // Load the model
        $this->load->model('Bom_model');

        // Read DataTables parameters
        $start = $this->input->post('start');
        $length = $this->input->post('length');
        $draw = $this->input->post('draw');
        $search = $this->input->post('search')['value'];

        // Fetch the data
        $data = $this->Bom_model->getBomData($start, $length, $search);

        // Total records, before filtering
        $totalRecords = $this->Bom_model->getTotalRecords();

        // Total records, after filtering
        $totalFilteredRecords = $this->Bom_model->getTotalFilteredRecords($search);

        // Prepare the response in DataTables format
        $response = array(
            "draw" => intval($draw),
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalFilteredRecords,
            "data" => $data
        );

        // Send the response in JSON format
        echo json_encode($response);
    }
 

    // UPDATE Material BOM
    function EditBomMaterial() {
        $id = $this->input->post('id');
        $materialID = $this->input->post('material_id');
        $Data = array(
            'Id_fg' => $this->input->post('id_fg'),
            'Id_material' => $this->input->post('material_id'),
            'Material_desc' => $this->input->post('material_desc'),
            'Material_type' => $this->input->post('material_type'),
            'Uom' => $this->input->post('uom'),
            'Qty' => $this->input->post('qty'),
            'Upddt' => date('Y-m-d H:i:s'), // Adjusted date format
            'Updby' => $this->input->post('user')
        );
    
        $this->db->where('Id_bom', $id);  
        $this->db->update('bom', $Data);
        $check_update = $this->db->affected_rows();
    
        if ($check_update > 0) {
            $this->session->set_flashdata('success_EditBomMaterial', 'Material ' . $materialID . ' has been successfully updated');
        } else {
            $this->session->set_flashdata('failed_EditBomMaterial', 'Material ' . $materialID . ' failed to update');
        }
    
        redirect('master/bom');
    } 


    // DELETE Material BOM
    function deleteMaterialBom(){
        $id = $this->input->post('id');

        $Data = [
            'is_active' => 0,
            'Upddt' => date('Y-d-m H:i'),
            'Updby' => $this->input->post('user')
        ];


        $this->db->where('Id_bom',$id);  
        $this->db->update('bom', $Data);
        $this->session->set_flashdata('DELETED',
        '
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="width: 40%">
        <i class="bi bi-check-circle me-1"></i> BOM\'s Material successfully deleted
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        ');

        redirect('master/bom');
    }

    function deleteMultipleMaterialBom(){
        $data = $this->input->post('selectedItems');
    
        $DeleteDataBom = 0; 
    
        foreach ($data as $dt) {
            $DataBOM = [
                'is_active' => 0,
                'Upddt' => date('Y-m-d H:i'),
                'Updby' => $this->input->post('user')
            ];
            
            // Ensure that 'id' is correctly passed and processed
            $this->MModel->updateMultipleDataBom('bom', $dt['id'], $DataBOM);
            $check_delete = $this->db->affected_rows();
            
            if ($check_delete > 0) {
                $DeleteDataBom += 1;
            }
        }
    
        // Determine the result based on the number of successful deletions
        $result = $DeleteDataBom > 0 ? 1 : 0;
    
        echo json_encode($result);
    }
    
}