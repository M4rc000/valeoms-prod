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

	public function bom()
	{
        $data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $data['name'] = $this->db->get_where('user', ['name' => $this->session->userdata('name')])->row_array();

        $data['title'] = 'BOM';
        $data['bom'] = $this->MModel->getBom();
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');   
        $this->load->view('templates/sidebar');   
        $this->load->view('master/bom', $data);
        $this->load->view('templates/footer');
	}
}
