<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class User extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->library('form_validation');
        $this->load->model('User_model', 'UModel');
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
	
    public function change_password()
	{
        $data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $data['name'] = $this->db->get_where('user', ['name' => $this->session->userdata('name')])->row_array();
        
        $data['menus'] = $this->uri->segment(1);
        
        $data['title'] = 'Change password';
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');   
        $this->load->view('templates/sidebar');   
        $this->load->view('user/change_password', $data);
        $this->load->view('templates/footer');
	}

        function changenewpassword(){
            $user = $this->input->post('user');
            $id = $this->input->post('id');
            $newpassword2 = $this->input->post('newpassword2');
            $Data = [
                'password' => password_hash($newpassword2, PASSWORD_DEFAULT),
                'Upddt' => date('Y-m-d H:i'),
                'Updby' => $user
            ];
            
            $this->UModel->updateData('user', $id, $Data);

            $query = $this->db->affected_rows();
            if($query == 1){
                $result = 'success';
            }
            else{
                $result = 'failed';
            }

            echo json_encode($result);
        }
    
    public function home(){
        $data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $data['name'] = $this->db->get_where('user', ['name' => $this->session->userdata('name')])->row_array();
        
        $data['title'] = 'Home';
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar');   
        $this->load->view('templates/sidebar');   
        $this->load->view('user/home', $data);
        $this->load->view('templates/footer');
    }
}









