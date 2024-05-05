<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->library('form_validation');
    }
	
	public function index()
	{
        $url = $_SERVER['REQUEST_URI'];
        $userUrl = '/user';
        if (substr($url, -strlen($userUrl)) === $userUrl) {
            header('Location: ' . rtrim($url, '/') . '/');
            exit();
        }

        $data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $data['menus'] = $this->uri->segment(1);
        
        $data['title'] = 'My Profile';
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');   
        $this->load->view('templates/sidebar');   
        $this->load->view('user/index', $data);
        $this->load->view('templates/footer');
	}
	
	public function change_password()
	{
        $data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $data['title'] = 'Change Password';
        $data['menus'] = $this->uri->segment(1); 
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');   
        $this->load->view('templates/sidebar');   
        $this->load->view('user/change_password', $data);
        $this->load->view('templates/footer');
	}

    public function friend_list()
    {
        $data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $data['title'] = 'Friend List'; 
        
        $data['menus'] = $this->uri->segment(1); 
        $this->load->model('User_model','UModel');
        $data['users'] = $this->UModel->getAllUsers();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');   
        $this->load->view('templates/sidebar');   
        $this->load->view('user/friend_list', $data);
        $this->load->view('templates/footer');
    }
}
