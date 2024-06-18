<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');
class Quality extends CI_Controller {
    
    public function __construct(){
        parent::__construct();
        is_logged_in();
        $this->load->library('form_validation');
        $this->load->model('Quality_model', 'QModel');
    }
	
	public function index(){
        $data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $data['name'] = $this->db->get_where('user', ['name' => $this->session->userdata('name')])->row_array();
        
        $data['title'] = 'Material Requests';
        $data['materials'] = $this->QModel->getAllMaterials(); 

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);   
        $this->load->view('templates/sidebar', $data);   
        $this->load->view('quality/material_request', $data);
        $this->load->view('templates/footer');
	}  
	
    function getMaterial(){
        $materialID = htmlspecialchars($this->input->post('material_id'));
        $result = $this->db->query("SELECT * FROM material_list WHERE Id_material = '$materialID' AND is_active = 1")->result_array();
    
        echo json_encode($result);
    }

    function getCalculateMaterial(){
        $materialID = $this->input->post('material_id');
        $materialDesc = $this->input->post('material_desc');
        $materialNeed = $this->input->post('material_need');
        $materialUom = $this->input->post('material_uom');
        $Id_request = $this->QModel->getLastIdReturn();

        $Data = [
            'Id_request' => $Id_request,
            'Id_material' => $materialID,
            'Material_desc' => $materialDesc,
            'Material_need' => floatval($materialNeed),
            'Uom' => $materialUom,
            'status' => 1, // 1: PENDING, 0: Approved
            'Crtdt' => date('Y-m-d H:i:s'), 
            'Crtby' => $this->input->post('user'),
            'Upddt' => date('Y-m-d H:i:s'), 
            'Updby' => $this->input->post('user'),
        ];

        $this->QModel->insertData('quality_request', $Data);

        $Request_result = $this->db->query("SELECT * FROM quality_request WHERE Id_request = '$Id_request' AND status = 1")->result_array();
        $Box_result = $this->db->query("SELECT 
                b.id_box, 
                b.no_box, 
                b.weight, 
                b.sloc, 
                s.SLoc,
                bd.product_id, 
                bd.material_desc, 
                bd.total_qty, 
                bd.total_qty_real, 
                bd.uom
            FROM 
                box b
            LEFT JOIN 
                list_storage bd ON b.id_box = bd.id_box
            LEFT JOIN 
                storage s ON b.sloc = s.Id_storage
            WHERE 
                bd.product_id = '$materialID'")->result_array();  

        $result = [
            'Request_result' => $Request_result,
            'Box_result' => $Box_result
        ];
        echo json_encode($result);
    }

    public function material_return(){
        $data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $data['name'] = $this->db->get_where('user', ['name' => $this->session->userdata('name')])->row_array();
        
        $data['title'] = 'Material Return';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);   
        $this->load->view('templates/sidebar', $data);   
        $this->load->view('quality/material_return', $data);
        $this->load->view('templates/footer');
	}  
    
    function getMaterialDesc(){
        $materialID = htmlspecialchars($this->input->post('materialID'));
        $result = $this->db->query("SELECT Material_desc, Material_type, Uom FROM material_list WHERE Id_material = '$materialID' AND is_active = 1")->result_array();
    
        echo json_encode($result);
    }

    function AddHighRack(){
        $materialData = $this->input->post('materialData');
        $no_box = $this->QModel->generateFormattedBoxNumber();
    
        $DataBox = [
            'no_box' => $no_box,
            'weight' => $this->input->post('weight'),
            'sloc' => '',
            'box_type' => 'HIGH',
            'crtby' => $this->input->post('user'),
            'crtdt' => date('Y-m-d H:i:s'), 
        ];
        $this->QModel->insertData('box', $DataBox);
    
        // Fetch the ID of the inserted box
        $id_table_box = $this->db->query("SELECT * FROM `box` WHERE no_box ='$no_box'")->row_array(); 
    
        foreach($materialData as $md){
            $DataBoxDetail = [
                'id_box' => $id_table_box['id_box'],
                'id_material' => $md['material_id'],
                'material_desc' => $md['material_desc'],
                'crtdt' => date('Y-m-d H:i:s'),
                'crtby' => $this->input->post('user')
            ];
            $this->QModel->insertData('box_detail', $DataBoxDetail);
        }
    
        foreach($materialData as $md){
            $DataListStorage = [
                'product_id' => $md['material_id'],
                'material_desc' => $md['material_desc'],
                'sloc' => '',
                'uom' => $md['uom'],
                'total_qty' => $md['qty'],
                'total_qty_real' => $md['qty'],
                'id_box' => $id_table_box['id_box'], 
                'created_at' => date('Y-m-d H:i:s'), 
                'created_by' => $this->input->post('user')
            ];
            $this->QModel->insertData('list_storage', $DataListStorage);
        }
    
        $DataReturnWarehouse = [
            'No_box' => $id_table_box['no_box'],
            'status' => 1, // 1: PENDING, 0: APPROVED
            'Crtdt' => date('Y-m-d H:i:s'),
            'Crtby' => $this->input->post('user'),
            'Upddt' => date('Y-m-d H:i:s'),
            'Updby' => $this->input->post('user')
        ];
        $this->QModel->insertData('return_warehouse', $DataReturnWarehouse);
    
        echo json_encode(['no_box' => $id_table_box['no_box']]);
    }
    
    function AddMediumRack(){
        $materialData = $this->input->post('materialData');
        $no_box = $this->QModel->generateFormattedBoxNumber();
    
        $DataBox = [
            'no_box' => $no_box,
            'weight' => $this->input->post('weight'),
            'sloc' => '',
            'box_type' => 'MEDIUM',
            'crtby' => $this->input->post('user'),
            'crtdt' => date('Y-m-d H:i:s'),
        ];
        $this->QModel->insertData('box', $DataBox);
    
        // Fetch the ID of the inserted box
        $id_table_box = $this->db->query("SELECT * FROM `box` WHERE no_box ='$no_box'")->row_array(); 
    
        foreach($materialData as $md){
            $DataBoxDetail = [
                'id_box' => $id_table_box['id_box'],
                'id_material' => $md['material_id'],
                'material_desc' => $md['material_desc'],
                'crtdt' => date('Y-m-d H:i:s'),
                'crtby' => $this->input->post('user')
            ];
            $this->QModel->insertData('box_detail', $DataBoxDetail);
        }
    
        foreach($materialData as $md){
            $DataListStorage = [
                'product_id' => $md['material_id'],
                'material_desc' => $md['material_desc'],
                'sloc' => '',
                'uom' => $md['uom'],
                'total_qty' => $md['qty'],
                'total_qty_real' => $md['qty'],
                'id_box' => $id_table_box['id_box'], 
                'created_at' => date('Y-m-d H:i:s'), 
                'created_by' => $this->input->post('user')
            ];
            $this->QModel->insertData('list_storage', $DataListStorage);
        }
    
        $DataReturnWarehouse = [
            'No_box' => $id_table_box['no_box'],
            'status' => 1, // 1: PENDING, 0: APPROVED
            'Crtdt' => date('Y-m-d H:i:s'), 
            'Crtby' => $this->input->post('user'),
            'Upddt' => date('Y-m-d H:i:s'), 
            'Updby' => $this->input->post('user')
        ];
        $this->QModel->insertData('return_warehouse', $DataReturnWarehouse);
    
        echo json_encode(['no_box' => $id_table_box['no_box']]);
    }
}