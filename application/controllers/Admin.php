<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');
class Admin extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->model('Admin_model','AModel');
        $this->load->model('Warehouse_model', 'WModel');
    }
	
	public function index(){
        $data['title'] = 'Dashboard';

        $data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $data['name'] = $this->db->get_where('user', ['name' => $this->session->userdata('name')])->row_array();
        
        $data['user'] = $this->AModel->getAllUsers();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);   
        $this->load->view('templates/sidebar', $data);   
        $this->load->view('admin/index', $data);
        $this->load->view('templates/footer');
	}

    public function manage_user() {
        $data['title'] = 'Manage User';

        $data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $data['name'] = $this->db->get_where('user', ['name' => $this->session->userdata('name')])->row_array();


        $data['users'] = $this->AModel->getAllUsers();
        $data['roles'] = $this->AModel->getAllRoles();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('admin/manage_user', $data);
        $this->load->view('templates/footer');
    }

	public function manage_role(){
        $data['title'] = 'Manage Role';

        $data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $data['name'] = $this->db->get_where('user', ['name' => $this->session->userdata('name')])->row_array();

        $this->load->model('Admin_model','AModel');
        
        $data['roles'] = $this->AModel->getAllRoles();
        $data['menu'] = $this->AModel->getAllMenu();
        $data['mensub'] = $this->AModel->getMenSub();
        $data['lastRoleId'] = $this->AModel->getLastRoleId();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);   
        $this->load->view('templates/sidebar',$data);   
        $this->load->view('admin/manage_user_role',$data);
        $this->load->view('templates/footer');
	}

    public function UpdateConfigRole() {
        $role_id = $this->input->post('role');
        $sub_menu = $this->input->post('sub_menu');
        $menu_ids = $this->input->post('menu_ids');
        $submenu_ids = $this->input->post('submenu_ids');
        $all_sub_menus = $this->input->post('all_sub_menus');
    
        // Delete existing data
        $this->db->where('role_id', $role_id);
        $this->db->delete('user_access_submenu');
    
        // Insert new access configuration
        foreach ($all_sub_menus as $index => $submenu_id) {
            $menu_id = $menu_ids[$index];
            if (isset($sub_menu[$submenu_id])) {
                $data = [
                    'role_id' => $role_id,
                    'menu_id' => $menu_id,
                    'submenu_id' => $submenu_id,
                ];
                $this->db->insert('user_access_submenu', $data);
            }
        }
    
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Role configuration updated!</div>');
        redirect('admin/manage_role');
    }      

	public function manage_menu(){
        $data['title'] = 'Manage Menu';

        $data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $data['name'] = $this->db->get_where('user', ['name' => $this->session->userdata('name')])->row_array();


        $this->load->model('Admin_model','AModel');
        
        $data['menus'] = $this->AModel->getAllMenu();
        $data['lastMenuId'] = $this->db->query("SELECT id FROM `user_menu` ORDER BY id DESC LIMIT 1")->result_array();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);   
        $this->load->view('templates/sidebar');   
        $this->load->view('admin/manage_menu',$data);
        $this->load->view('templates/footer');
	}

	public function manage_sub_menu()
	{
        $data['title'] = 'Manage Sub-Menu';

        $data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $data['name'] = $this->db->get_where('user', ['name' => $this->session->userdata('name')])->row_array();


        $this->load->model('Admin_model','AModel');
        
        $data['menus'] = $this->AModel->getAllMenu();
        $data['submenus'] = $this->AModel->getAllSubMenu();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);   
        $this->load->view('templates/sidebar');   
        $this->load->view('admin/manage_sub_menu',$data);
        $this->load->view('templates/footer');
	}


    // ACTION
        // MANAGE USER
        public function AddUser() {
            $Data = array(
                'name' => $this->input->post('name'),
                'username' => $this->input->post('username'),
                'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                'gender' => $this->input->post('gender'),
                'role_id' => $this->input->post('role'),
                'is_active' => $this->input->post('active'),
                'date_joined' => date('d-m-Y H:i')
            );

            $this->AModel->insertData('user', $Data);

            $check_insert = $this->db->affected_rows();

            if($check_insert > 0){
                $this->session->set_flashdata('SUCCESS_AddUser','New user has been successfully added');
            }
            else{
                $this->session->set_flashdata('FAILED_AddUser','Failed to add a new user');
            }

            redirect('admin/manage_user');
        }

        public function EditUser() {
            $id = $this->input->post('id');
                $Data = array(
                    'name' => $this->input->post('name'),
                    'username' => $this->input->post('username'),
                    'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                    'gender' => $this->input->post('gender'),
                    'role_id' => $this->input->post('role'),
                    'is_active' => $this->input->post('active'),
                    'Upddt' => date('Y-m-d H:i')
                );

            $this->AModel->updateData('user', $id, $Data);
            $check_insert = $this->db->affected_rows();

            if($check_insert > 0){
                $this->session->set_flashdata('SUCCESS_EditUser','User has been successfully updated');
            }
            else{
                $this->session->set_flashdata('FAILED_EditUser','Failed to update a user');
            }

            redirect('admin/manage_user'); 
        }

        public function deleteUser() {        
            $id = $this->input->post('id');
            $this->AModel->deleteData('user', $id);
        
            $check_insert = $this->db->affected_rows();

            if($check_insert > 0){
                $this->session->set_flashdata('SUCCESS_deleteUser','User has been successfully deleted');
            }
            else{
                $this->session->set_flashdata('FAILED_deleteUser','Failed to delete a user');
            }

            redirect('admin/manage_user'); 
        }
        

        // MANAGE USER ROLE
        public function addRole() {
            $id = $this->input->post('role_id');
            $role = $this->input->post('role');

            $Data = array(
                'id' => $id,
                'role' => $role,
                'crtdt' => date('d-m-Y h:i'),
                'crtby' => $this->input->post('user'),
                'upddt' => date('d-m-Y h:i'),
                'updby' => $this->input->post('user')
            );

            $this->AModel->insertData('user_role', $Data);
            $check_insert = $this->db->affected_rows();

            if($check_insert > 0){
                $this->session->set_flashdata('SUCCESS_addRole','New role has successfully added');
            }
            else{
                $this->session->set_flashdata('FAILED_addRole','Failed to add a new role');
            }

            redirect('admin/manage_role');
        }


        public function deleteRole() {
            $this->load->model('Admin_model','AModel');
        
            $id = $this->input->post('id');
            $this->AModel->deleteData('user_role', $id);

            $this->session->set_flashdata('DELETED','<div class="alert alert-success alert-dismissible fade show" role="alert">
                Role successfully deleted
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
                </div>');
            header("Location: " . base_url('admin/manage_role'));       
        }

        public function roleAccess($role_id){
            $data['title'] = 'Role Access';
            $data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
            $data['name'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();

            
            $data['role'] = $this->db->get_where('user_role', ['id' => $role_id])->row_array();

            $data['menu'] = $this->db->get('user_menu')->result_array();
            $data['accessmenu'] = $this->AModel->getMenuAccess($role_id);
            $data['accesssubmenu'] = $this->AModel->getSubMenuAccess($role_id);
            $data['roles'] = $this->AModel->getAllRoles();

            $this->db->select('user_menu.*');
            $this->db->from('user_menu');
            $this->db->join('user_access_menu', 'user_menu.id = user_access_menu.menu_id AND user_access_menu.role_id = ' . $role_id, 'left');
            $this->db->where('user_access_menu.menu_id IS NULL');
            $data['menus'] = $this->db->get()->result_array();

            $this->db->select('user_sub_menu.*');
            $this->db->from('user_sub_menu');
            $this->db->join('user_access_submenu', 'user_sub_menu.id = user_access_submenu.submenu_id AND user_access_submenu.role_id = ' . $role_id, 'left');
            $this->db->where('user_access_submenu.submenu_id IS NULL');
            $data['submenus'] = $this->db->get()->result_array();
             
            $this->load->view('templates/header', $data);
            $this->load->view('templates/navbar', $data);   
            $this->load->view('templates/sidebar');   
            $this->load->view('admin/role-access',$data);
            $this->load->view('templates/footer');

            $this->session->set_flashdata('role_access','<div class="alert alert-success alert-dismissible fade show" role="alert">
            The access has been changed!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
            </div>');
        }
            function addRoleAccessMenu(){
                $role_id = $this->input->post('role_id');
                $data = [
                    'role_id' => $role_id,
                    'menu_id' => $this->input->post('menu_id')
                ];

                $this->AModel->insertData('user_access_menu', $data);
                $this->session->set_flashdata('success', 'New menu Access permissions have been added');
                redirect('admin/roleAccess/'. $role_id);
            }

            function DeleteRoleAccessMenu(){
                $id = $this->input->post('id');
                $role_id = $this->input->post('role_id');
                $this->AModel->deleteData ('user_access_menu', $id);
                $this->session->set_flashdata('success', 'Menu Access permissions have been deleted');
                redirect('admin/roleAccess/'. $role_id);
            }

            function addRoleAccessSubMenu(){
                $role_id = $this->input->post('role_id');
                $data = [
                    'role_id' => $role_id,
                    'menu_id' => $this->input->post('meenu_id'),
                    'submenu_id' => $this->input->post('submenu_id'),
                ];

                $this->AModel->insertData('user_access_submenu', $data);
                $this->session->set_flashdata('success', 'New Submenu Access permissions have been added');
                redirect('admin/roleAccess/'. $role_id);
            }

            function DeleteRoleAccessSubMenu(){
                $id = $this->input->post('id');
                $role_id = $this->input->post('role_id');
                $this->AModel->deleteData ('user_access_submenu', $id);
                $this->session->set_flashdata('success', 'Submenu Access permissions have been deleted');
                redirect('admin/roleAccess/'. $role_id);
            }

            function getSubMenuBasedOnMenu(){
                $menu_id = $this->input->post('menu_id');
                $role_id = $this->input->post('role_id');

                $this->db->select("user_sub_menu.*");
                $this->db->from("user_sub_menu");
                $this->db->join("user_access_submenu", "user_sub_menu.id = user_access_submenu.submenu_id AND user_access_submenu.role_id = $role_id", "left outer");
                $this->db->where("user_access_submenu.submenu_id IS NULL");
                $this->db->where("user_sub_menu.menu_id", $menu_id);
                $query = $this->db->get();
                $result = $query->result_array();
                echo json_encode($result);
            }

        // MANAGE MENU
        public function AddMenu() {
            $Data = array(
                'id' => $this->input->post('id'),
                'menu' => $this->input->post('menu'),
                'crtdt' => date('d-m-Y H:i'),
                'crtby' => $this->input->post('user'),
                'upddt' => date('d-m-Y H:i'),
                'updby' => $this->input->post('user')
            );

            $this->AModel->insertData('user_menu', $Data);

            $check_insert = $this->db->affected_rows();

            if($check_insert > 0){
                $this->session->set_flashdata('SUCCESS_AddMenu','New menu has been successfully added');
            }
            else{
                $this->session->set_flashdata('FAILED_AddMenu','Failed to add new menu');
            }
            redirect('admin/manage_menu');
        }

        public function editMenu() {
            $id = $this->input->post('id');
            $Data = array(
                'menu' => $this->input->post('menu'),
                'upddt' => date('d-m-Y H:i'),
                'updby' => $this->input->post('user')
            );

            $this->AModel->updateData('user_menu', $id, $Data);

            $check_insert = $this->db->affected_rows();

            if($check_insert > 0){
                $this->session->set_flashdata('SUCCESS_editMenu','Menu has been successfully updated');
            }
            else{
                $this->session->set_flashdata('FAILED_editMenu','Failed to update the menu');
            }

            redirect('admin/manage_menu');
        }

        public function deleteMenu() {
            $this->load->model('Admin_model','AModel');
        
            $id = $this->input->post('id');
        
            $this->AModel->deleteData('user_menu', $id);

            $check_insert = $this->db->affected_rows();

            if($check_insert > 0){
                $this->session->set_flashdata('SUCCESS_deleteMenu','Menu has been successfully deleted');
            }
            else{
                $this->session->set_flashdata('FAILED_deleteMenu','Failed to delete the menu');
            }

            redirect('admin/manage_menu');
        }

        // MANAGE SUBMENU
        public function AddSubMenu() {
            $Data = array(
                'menu_id' => $this->input->post('menu_id'),
                'title' => $this->input->post('title'),
                'url' => $this->input->post('url'),
                'icon' => $this->input->post('icon'),
                'is_active' => $this->input->post('active'),
                'crtdt' => date('d-m-Y H:i'),
                'crtby' => $this->input->post('user'),
                'upddt' => date('d-m-Y H:i'),
                'updby' => $this->input->post('user')
            );

            $this->AModel->insertData('user_sub_menu', $Data);
            $check_insert = $this->db->affected_rows();

            if($check_insert > 0){
                $this->session->set_flashdata('SUCCESS_AddSubMenu','New a submenu has been successfully added');
            }
            else{
                $this->session->set_flashdata('FAILED_AddSubMenu','Failed to add a new submenu');
            }
        
            redirect('admin/manage_sub_menu');
        }

        public function editSubMenu() {
            $id = $this->input->post('id');
            $Data = array(
                'menu_id' => $this->input->post('menu_id'),
                'title' => $this->input->post('title'),
                'url' => $this->input->post('url'),
                'icon' => $this->input->post('icon'),
                'is_active' => $this->input->post('active'),
                'upddt' => date('d-m-Y H:i'),
                'updby' => $this->input->post('user')
            );

            $this->AModel->updateData('user_sub_menu', $id, $Data);
            $check_insert = $this->db->affected_rows();

            if($check_insert > 0){
                $this->session->set_flashdata('SUCCESS_editSubMenu','Submenu has been successfully updated');
            }
            else{
                $this->session->set_flashdata('FAILED_editSubMenu','Failed to update a submenu');
            }

            redirect('admin/manage_sub_menu');
        }

        public function DeleteSubMenu() {
            $this->load->model('Admin_model','AModel');
        
            $id = $this->input->post('id');
        
            $this->AModel->deleteData('user_sub_menu', $id);
            $check_insert = $this->db->affected_rows();

            if($check_insert > 0){
                $this->session->set_flashdata('SUCCESS_DeleteSubMenu','Submenu has been successfully deleted');
            }
            else{
                $this->session->set_flashdata('FAILED_DeleteSubMenu','Failed to delete a submenu');
            }

            redirect('admin/manage_sub_menu');
        }

    public function manage_storage(){
	
        $data['title'] = 'Manage Storage';

        $data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $data['name'] = $this->db->get_where('user', ['name' => $this->session->userdata('name')])->row_array();

        $this->load->model('Warehouse_model', 'WModel');
        $data['storage'] = $this->AModel->getListStorage();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);   
        $this->load->view('templates/sidebar');   
        $this->load->view('admin/manage_storage',$data);
        $this->load->view('templates/footer');
    }

        function getBoxBySloc(){
            $idStorage = $this->input->post('idStorage');

            $query = $this->db->query("SELECT b.id_box, b.no_box, b.weight, b.box_type
                FROM box b
                LEFT JOIN list_storage ls ON ls.id_box = b.id_box
                WHERE ls.sloc = '$idStorage'")->row_array();
            $query_length = $this->db->query("SELECT b.id_box, b.no_box, b.weight, b.box_type
                FROM box b
                LEFT JOIN list_storage ls ON ls.id_box = b.id_box
                WHERE ls.sloc = '$idStorage'")->num_rows();

            $result = [
                'result' => $query,
                'result_length' => $query_length
            ];

            echo json_encode($result);
        }
    
    public function manage_box() {
        $data['title'] = 'Manage Box';
    
        $data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $data['name'] = $this->db->get_where('user', ['name' => $this->session->userdata('name')])->row_array();
    
        $config['base_url'] = base_url('admin/manage_box');
        $config['total_rows'] = $this->AModel->countAllBoxes();
        $config['per_page'] = 50;
    
        // Add these two lines for pagination styling (Bootstrap 4)
        $config['full_tag_open'] = '<nav aria-label="Page navigation example"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';
        $config['first_link'] = '&laquo;';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = '&raquo;';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['next_link'] = '&rsaquo;';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = '&lsaquo;';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['attributes'] = array('class' => 'page-link');
    
        $this->pagination->initialize($config);
    
        $data['start'] = $this->uri->segment(3, 0);
        $data['list_box'] = $this->AModel->getBoxes($config['per_page'], $data['start']);
    
        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);   
        $this->load->view('templates/sidebar');   
        $this->load->view('admin/manage_box', $data);
        $this->load->view('templates/footer');
    }   
}