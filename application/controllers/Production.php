<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');
class Production extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->library('form_validation');
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
}