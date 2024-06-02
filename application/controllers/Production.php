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
        $data['name'] = $this->db->get_where('user', ['name' => $this->session->userdata('name')])->row_array();
        
        $data['title'] = 'Material Requests';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);   
        $this->load->view('templates/sidebar', $data);   
        $this->load->view('production/material_request', $data);
        $this->load->view('templates/footer');
	}   

    function getProduct()
    {
        $productID = $this->input->post('productID');
        $result = $this->db->query("SELECT Id_fg, Fg_desc FROM bom WHERE Id_fg = '$productID'")->result_array();
       
        echo json_encode($result);
    }

    function getProductData(){
        $productID = $this->input->post('productID');
        $result = $this->db->query("SELECT Id_fg, Fg_desc FROM bom WHERE Id_fg = '$productID'")->result_array();
       
        echo json_encode($result);
    }

    public function kitting(){
        $data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $data['name'] = $this->db->get_where('user', ['name' => $this->session->userdata('name')])->row_array();
        
        $data['title'] = 'Kitting';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);   
        $this->load->view('templates/sidebar', $data);   
        $this->load->view('production/kitting', $data);
        $this->load->view('templates/footer');
    }

    function getBox()
    {
        $boxID = $this->input->post('boxID');
        $result = $this->db->query("SELECT * FROM box WHERE Id_box = '$boxID'")->result_array();
       
        echo json_encode($result);
    }

    function getReqNo()
    {
        $reqNo = $this->input->post('reqNo');
        $result = $this->db->query("SELECT * FROM production_request WHERE production_request_no = '$reqNo'")->result_array();
       
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
    
    
}