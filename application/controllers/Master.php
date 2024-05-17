<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->library('form_validation');
        $this->load->model('Master_model', 'MModel');
    }
	
	public function index()
	{
        $data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $data['name'] = $this->db->get_where('user', ['name' => $this->session->userdata('name')])->row_array();

        $data['title'] = 'Material List';
        $data['material_list'] = $this->MModel->getListMaterial();
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');   
        $this->load->view('templates/sidebar');   
        $this->load->view('master/material_list', $data);
        $this->load->view('templates/footer');
	}

    // CREATE MATERIAL LIST
    public function AddMaterialList(){
        $Data = array(
            'Id_material' => $this->input->post('material_id'),
            'Material_desc' => $this->input->post('material_desc'),
            'Material_type' => $this->input->post('material_type'),
            'Uom' => $this->input->post('uom'),
            'Family' => $this->input->post('family'),
            'crtdt' => date('Y-d-m H:i'),
            'crtby' => $this->input->post('user'),
            'upddt' => date('Y-d-m H:i'),
            'updby' => $this->input->post('user')
        );


        $this->MModel->insertData('material_list', $Data);
        $this->session->set_flashdata('SUCCESS',
        '
            <div class="alert alert-success alert-dismissible fade show" role="alert" style="width: 40%">
                <i class="bi bi-check-circle me-1"></i> New Material List successfully added
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        ');
        redirect('master/');
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
        $this->session->set_flashdata('EDIT',
        '
        <div class="row mt-2">
            <div class="col-md">
                <div class="alert alert-success alert-dismissible fade show" role="alert" style="width: 40%">
                    <i class="bi bi-check-circle me-1"></i> Material List successfully updated
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
        ');
        
        redirect('master/');
    }

    // DELETE MATERIAL LIST
    public function DeleteMaterialID(){
        $id = $this->input->post('id');
        $this->MModel->deleteData('material_list', $id);
        $this->session->set_flashdata('DELETED',
        '
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="width: 40%">
            <i class="bi bi-check-circle me-1"></i> Material List successfully deleted
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        ');

        redirect('master/');  
    }


	public function bom()
	{
        $data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $data['name'] = $this->db->get_where('user', ['name' => $this->session->userdata('name')])->row_array();

        $data['title'] = 'BOM';
        $data['bom'] = $this->MModel->getBom();
        $data['materials'] = $this->MModel->getListMaterial();
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');   
        $this->load->view('templates/sidebar');   
        $this->load->view('master/bom', $data);
        $this->load->view('templates/footer');
	}


    // READ BOM
    public function getBomList(){
        $Id_product = htmlspecialchars($this->input->post('Id_product'));
        $result = $this->db->query("SELECT * FROM bom WHERE Id_fg = '$Id_product' AND is_active = 1")->result_array();
       
        echo json_encode($result);
    }    

    // UPDATE Material BOM
    public function EditBomMaterial(){
        $id = $this->input->post('id');
        
        $Data = array(
            'Id_fg' => $this->input->post('id_fg'),
            'Id_material' => $this->input->post('material_id'),
            'Material_desc' => $this->input->post('material_desc'),
            'Material_type' => $this->input->post('material_type'),
            'Uom' => $this->input->post('uom'),
            'Qty' => $this->input->post('qty'),
            'Upddt' => date('Y-d-m H:i'),
            'Updby' => $this->input->post('user')
        );

        $this->db->where('Id_bom',$id);  
        $this->db->update('bom', $Data);

        $this->session->set_flashdata('EDIT',
        '
        <div class="row mt-2">
            <div class="col-md">
                <div class="alert alert-success alert-dismissible fade show" role="alert" style="width: 40%">
                    <i class="bi bi-check-circle me-1"></i> Material Bom successfully updated
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
        ');
        
        redirect('master/bom');
    }

    // DELETE Material BOM
}