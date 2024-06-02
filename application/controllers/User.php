<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class User extends CI_Controller {

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
        
        $data['menus'] = $this->uri->segment(1);
        
        $data['title'] = 'My Profile';
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');   
        $this->load->view('templates/sidebar');   
        $this->load->view('user/index', $data);
        $this->load->view('templates/footer');
	}

}
