<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');
class Production extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->library('form_validation');
        $this->load->model('Production_model', 'PModel');
    }
	
	public function index()
	{
        $data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $data['name'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        
        $data['title'] = 'Material Requests';

        $data['boms'] = $this->PModel->getAllBoms();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);   
        $this->load->view('templates/sidebar', $data);   
        $this->load->view('production/material_request', $data);
        $this->load->view('templates/footer');
	}   

    function getProduct(){
        $productID = $this->input->post('productID');
        $result = $this->db->query("SELECT Id_fg, Fg_desc FROM bom WHERE Id_fg = '$productID'")->result_array();
       
        echo json_encode($result);
    }

    function getProductData(){
        $productID = $this->input->post('productID');
        $productDesc = $this->input->post('productDesc');
        $product_plan_qty = $this->input->post('qty');
        $user = $this->input->post('user');
    
        // Get the last production plan
        $production_plan = $this->PModel->getLastProductionPlan();
    
        // Data for Table production_plan
        $Data = [
            'Production_plan' => $production_plan,
            'Id_fg' => $productID,
            'Fg_desc' => $productDesc,
            'Production_plan_qty' => $product_plan_qty,
            'Crtdt' => date('Y-m-d H:i'),
            'Crtby' => $user,
            'Upddt' => date('Y-m-d H:i'),
            'Updby' => $user
        ];
    
        // Insert data into production_plan table
        $this->PModel->insertData('production_plan', $Data);
    
        // Retrieve BOM data
        $result = $this->db->query("SELECT * FROM bom WHERE Id_fg = '$productID'")->result_array();
    
        // Prepare and insert data into production_plan_detail table
        foreach ($result as $row) {
            $detailData = [
                'Production_plan' => $production_plan,
                'Id_material' => $row['Id_material'],
                'Material_desc' => $row['Material_desc'],
                'Material_need' => $row['Qty'] * $product_plan_qty,
                'Uom' => $row['Uom'],
                'status' => 0, 
                'Crtdt' => date('Y-m-d H:i'),
                'Crtby' => $user,
                'Upddt' => date('Y-m-d H:i'),
                'Updby' => $user
            ];
    
            // Insert into production_plan_detail table
            $this->PModel->insertData('production_plan_detail', $detailData);
        }
    
        $results = $this->db->query("SELECT * FROM production_plan_detail WHERE Production_plan = '$production_plan'")->result_array();

        // Echo the result as JSON
        echo json_encode($results);
    }
    
    
    function getSlocStorage(){
        $materialId = $this->input->post('materialId');
        $result = $this->db->query("SELECT b.no_box, b.weight, b.sloc, bd.product_id, bd.material_desc, bd.total_qty, bd.total_qty_real, bd.uom FROM box b LEFT JOIN list_storage bd ON b.id_box = bd.id_box WHERE bd.product_id = '$materialId'
        ")->result_array();
       
        echo json_encode($result);
    }

    function AddMaterialRequest(){
        $user = $this->input->post('user');
        $materialData = $this->input->post('materialData');
        $Req_no = $this->PModel->getLastReqNo();
        $Production_plan = $this->input->post('production_plan');
        $Id_material = $this->input->post('Id_material');
        $Material_desc = $this->input->post('Material_desc');
        $result = 0;
        die;

        foreach ($materialData as $data) {
            $Data = [
                'Req_no' => $Req_no,
                'Production_plan' => $Production_plan,
                'Id_material' => $Id_material,
                'Material_desc' => $Material_desc,
                'Qty' => $data['qty'],
                'sloc' => $data['sloc'],
                'box_no' => $data['box_no'],
                'crtdt' => date('Y-d-m H:i'),
                'crtby' => $this->input->post('user'),
                'upddt' => date('Y-d-m H:i'),
                'updby' => $this->input->post('user')
            ];

            // Insert the data into the database
            $this->PModel->insertData('production_request', $Data);
            $result = 1;
        }
    
        $results = $this->db->query("SELECT * FROM production_plan_detail WHERE Production_plan = '$production_plan'")->result_array();

        echo json_encode($results);
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


    public function kanban_box(){
        $data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $data['name'] = $this->db->get_where('user', ['name' => $this->session->userdata('name')])->row_array();
        
        $data['title'] = 'Kanban Box';

        $data['kanbanlist'] = $this->PModel->getKanbanList();
        $data['kanban'] = $this->PModel->getLastKanbanID();
        $data['material_list'] = $this->PModel->getMaterialList();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);   
        $this->load->view('templates/sidebar', $data);   
        $this->load->view('production/kanban_box', $data);
        $this->load->view('templates/footer');
    }

    // ADD KANBAN BOX
    function AddKanbanBox(){
        $materialID = $this->input->post('material_id');

        $Data = array(
            'Id_kanban_box' => $this->input->post('kanbanBox_id'),
            'Id_material' => $materialID,
            'Material_desc' => $this->input->post('material_desc'),
            'Material_qty' => $this->input->post('qty'),
            'Product_plan' => $this->input->post('production_planning'),
            'crtdt' => date('Y-d-m H:i'),
            'crtby' => $this->input->post('user'),
            'upddt' => date('Y-d-m H:i'),
            'updby' => $this->input->post('user')
        );


        $this->session->set_flashdata('kanban_data', $Data);
        $Result = $this->PModel->insertData('kanban_box', $Data);

        if ($Result) {
            // Success
            $this->session->set_flashdata('ADD', '
                <div class="alert alert-success alert-dismissible fade show" role="alert" style="width: 40%;">
                    <i class="bi bi-check-circle me-1"></i> New Kanban Box successfully added
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            ');

        } else {
            // Failure
            $this->session->set_flashdata('ERROR', '
                <div class="alert alert-danger alert-dismissible fade show" role="alert" style="width: 40%;">
                    <i class="bi bi-exclamation-circle me-1"></i> Failed to add New Kanban Box
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            ');
        }
        redirect('production/kanban_box');
    }

    // PRINT KANBAN BOX
    public function print_kanban() {
        // Load user data
        $data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $data['name'] = $this->db->get_where('user', ['name' => $this->session->userdata('name')])->row_array();
        $data['title'] = 'Kitting';

        // Retrieve data from $_GET
        $data['materialID'] = $this->input->get('materialID');
        $data['materialDesc'] = $this->input->get('materialDesc');
        $data['materialQty'] = $this->input->get('materialQty');
        $data['proPlan'] = $this->input->get('proPlan');
    }

    function getMaterialList(){
        $materialID = $this->input->post('materialID');
        $result = $this->db->query("SELECT * FROM material_list WHERE Id_material = '$materialID'")->result_array();
       
        echo json_encode($result);
    }
    
    public function getKanbanImage(){
        $id_kanban_box = $this->input->post('id_kanban');
        $result = $this->db->query("SELECT * FROM kanban_box WHERE Id_kanban_box = '$id_kanban_box'")->result_array();
       
        echo json_encode($result);
    }

    // UPLOAD BARCODE
    public function SaveBarcode() {
        // Get the image data from the request
        $imageData = $this->input->post('imageData');
        $id_kanban_box = $this->input->post('id_kanban_box');
    
        if ($imageData) {
            // Remove the "data:image/png;base64," part
            $imageData = str_replace('data:image/png;base64,', '', $imageData);
            $imageData = str_replace(' ', '+', $imageData);
            $decodedImageData = base64_decode($imageData);
    
            // Set the file path and name
            $fileName = uniqid() . '.png';
            $filePath = './assets/img/kanban-barcode/' . $fileName;
    
            // Save the image file
            if (file_put_contents($filePath, $decodedImageData)) {
                // Get the id_kanban_box from the request
    
                // Prepare data for updating the database
                $data = [
                    'image' => $fileName
                ];
    
                // Update the database where id_kanban_box matches
                $this->db->where('id_kanban_box', $id_kanban_box);
                $this->db->update('kanban_box', $data);
    
                // Check if the update was successful
                if ($this->db->affected_rows() > 0) {
                    // Respond with success
                    echo json_encode(['status' => 'success', 'message' => 'QR code saved and updated successfully']);
                } else {
                    // Respond with error if the update failed
                    echo json_encode(['status' => 'error', 'message' => 'Failed to update database']);
                }
            } else {
                // Respond with error if the image file saving failed
                echo json_encode(['status' => 'error', 'message' => 'Failed to save QR code']);
            }
        } else {
            // Respond with error if no image data received
            echo json_encode(['status' => 'error', 'message' => 'No image data received']);
        }
    }
    
    public function material_return()
	{
        $data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $data['name'] = $this->db->get_where('user', ['name' => $this->session->userdata('name')])->row_array();
        
        $data['title'] = 'Material Return';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);   
        $this->load->view('templates/sidebar', $data);   
        $this->load->view('production/material_return', $data);
        $this->load->view('templates/footer');
	}

    function getslocbasedweight(){
        $weight = $this->input->post('weight');
        $result = $this->PModel->getsloc($weight);

        echo json_encode($result);
    }

    function getMaterialDesc(){
        $materialID = htmlspecialchars($this->input->post('materialID'));
        $result = $this->db->query("SELECT Material_desc, Material_type, Uom FROM material_list WHERE Id_material = '$materialID' AND is_active = 1")->result_array();
    
        echo json_encode($result);
    }

    function AddHighRack(){
        $materialData = $this->input->post('materialData');
        $no_box = $this->PModel->generateFormattedBoxNumber();
    
        $DataBox = [
            'no_box' => $no_box,
            'weight' => $this->input->post('weight'),
            'sloc' => '',
            'box_type' => 'HIGH',
            'crtby' => $this->input->post('user'),
            'crtdt' => date('Y-m-d H:i:s'), 
        ];
        $this->PModel->insertData('box', $DataBox);
    
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
            $this->PModel->insertData('box_detail', $DataBoxDetail);
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
            $this->PModel->insertData('list_storage', $DataListStorage);
        }
    
        $DataReturnWarehouse = [
            'No_box' => $id_table_box['no_box'],
            'status' => 1, // 1: PENDING, 0: APPROVED
            'Crtdt' => date('Y-m-d H:i:s'),
            'Crtby' => $this->input->post('user'),
            'Upddt' => date('Y-m-d H:i:s'),
            'Updby' => $this->input->post('user')
        ];
        $this->PModel->insertData('return_warehouse', $DataReturnWarehouse);
    
        echo json_encode(['no_box' => $id_table_box['no_box']]);
    }
    
    function AddMediumRack(){
        $materialData = $this->input->post('materialData');
        $no_box = $this->PModel->generateFormattedBoxNumber();
    
        $DataBox = [
            'no_box' => $no_box,
            'weight' => $this->input->post('weight'),
            'sloc' => '',
            'box_type' => 'MEDIUM',
            'crtby' => $this->input->post('user'),
            'crtdt' => date('Y-m-d H:i:s'),
        ];
        $this->PModel->insertData('box', $DataBox);
    
        $id_table_box = $this->db->query("SELECT * FROM `box` WHERE no_box ='$no_box'")->row_array(); 
        
        if($materialData){
            foreach($materialData as $md){
                $DataBoxDetail = [
                    'id_box' => $id_table_box['id_box'],
                    'id_material' => $md['material_id'],
                    'material_desc' => $md['material_desc'],
                    'crtdt' => date('Y-m-d H:i:s'),
                    'crtby' => $this->input->post('user')
                ];
                $this->PModel->insertData('box_detail', $DataBoxDetail);
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
                $this->PModel->insertData('list_storage', $DataListStorage);
            }
        }
    
        $DataReturnWarehouse = [
            'No_box' => $id_table_box['no_box'],
            'status' => 1, // 1: PENDING, 0: APPROVED
            'Crtdt' => date('Y-m-d H:i:s'), 
            'Crtby' => $this->input->post('user'),
            'Upddt' => date('Y-m-d H:i:s'), 
            'Updby' => $this->input->post('user')
        ];
        $this->PModel->insertData('return_warehouse', $DataReturnWarehouse);
    
        echo json_encode(['no_box' => $id_table_box['no_box']]);
    }
}