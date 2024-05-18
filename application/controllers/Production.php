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
        $result = $this->db->query("SELECT * FROM material_request WHERE Req_no = '$reqNo'")->result_array();
       
        echo json_encode($result);
    }

    public function kanban_box(){
        $data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $data['name'] = $this->db->get_where('user', ['name' => $this->session->userdata('name')])->row_array();
        
        $data['title'] = 'Kanban Box';

        $data['kanbanlist'] = $this->PModel->getKanbanList();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);   
        $this->load->view('templates/sidebar', $data);   
        $this->load->view('production/kanban_box', $data);
        $this->load->view('templates/footer');
    }

    // ADD KANBAN BOX
    function AddKanbanBox(){
        $productID = $this->input->post('product_id');
        $bomDesc = $this->PModel->getBomDesc($productID);

        $Data = array(
            'Id_product' => $productID,
            'Product_desc' => $bomDesc[0]['Fg_desc'],
            'Product_qty' => $this->input->post('qty'),
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
}