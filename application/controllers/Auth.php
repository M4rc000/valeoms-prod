<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');
class Auth extends CI_Controller {
	public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('session');
    }
	
    public function index()
    {
        if ($this->session->userdata('username')) {
            redirect('user');
        }
        $data['title'] = 'Login Page';
        $data['background'] = base_url('assets') . '/images/auth/login.jpg';

        $this->form_validation->set_rules('username', 'Username', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/auth_header',$data);
            $this->load->view('auth/index',$data);
            $this->load->view('templates/auth_footer');
        } else {
            // validasinya success
            $this->_login();
        }
    }

    private function _login()
    {
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        $user = $this->db->get_where('user', ['username' => $username])->row_array();

        // jika usernya ada
        if ($user) {
            // jika usernya aktif
            if ($user['is_active'] == 1) {
                // cek password
                if (password_verify($password, $user['password'])) {
                    $data = [
                        'username' => $user['username'],
                        'name' => $user['name'],
                        'role_id' => $user['role_id']
                    ];
                   $this->session->set_userdata($data);
                    if ($user['role_id'] == 1) {
                        redirect('admin');
                    }
                    elseif ($user['role_id'] == 2) {
                        redirect('warehouse');
                    }
                    else {
                        redirect('production');
                    }
                } else {
                    $this->session->set_flashdata('wrong_password','<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Your password is wrong!
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                    </div>');
                    // echo "password is wrong";
                    redirect('auth');
                }
            } else {
                $this->session->set_flashdata('wrong_username','<div class="alert alert-danger alert-dismissible fade show" role="alert">
                Your username has not been activated
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
                </div>');
                // echo "username is wrong";
                redirect('auth');
            }
        } else {
            $this->session->set_flashdata('not_active_username','<div class="alert alert-danger alert-dismissible fade show" role="alert">
            Your username has not been activated
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
            </div>');
            redirect('auth');
        }
    }


    public function logout()
    {
        $this->session->unset_userdata('username');
        $this->session->unset_userdata('role_id');
        
        $this->session->set_flashdata('logout','<div class="alert alert-danger alert-dismissible fade show" role="alert">
        You have been logout!
        </button>
        </div>');
        redirect('auth');
    }

}
