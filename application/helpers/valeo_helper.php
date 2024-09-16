<?php 
    function is_logged_in()
    {
        $ci = get_instance();
        if (!$ci->session->userdata('username')) {
            redirect('auth');
        } else {
            $role_id = $ci->session->userdata('role_id');
            $menu = $ci->uri->segment(1);
            // $submenu = $ci->uri->segment(2);

            $queryMenu = $ci->db->get_where('user_menu', ['menu' => $menu])->row_array();
            $menu_id = $queryMenu['id'];
            
            // $querySubMenu = $ci->db->get_where('user_sub_menu', ['title' => $submenu])->row_array();
            // $submenu_id = $querySubMenu['id'];

            $userAccess = $ci->db->get_where('user_access_menu', [
                'role_id' => $role_id,
                'menu_id' => $menu_id
            ]);

            if ($userAccess->num_rows() < 1) {
                redirect('auth/blocked');
            }
        }
    }

    // function is_logged_in()
    // {
    //     $ci = get_instance();
    //     if (!$ci->session->userdata('username')) {
    //         redirect('auth');
    //     } else {
    //         $role_id = $ci->session->userdata('role_id');
    //         $controller = $ci->router->fetch_class();
    //         $method = $ci->router->fetch_method();   
            
    //         if($method == 'index'){
    //             $url = $controller . '/';
    //         }
    //         else{
    //             $url = $controller . '/' . $method;
    //         }


    //         // echo $url;
    //         // echo '<br>';
    //         // echo '<br>';
    //         // echo $controller;
    //         // echo '<br>';
    //         // echo $method;
    //         // echo '<br>';

    //         $queryMenu = $ci->db->get_where('user_menu', ['menu' => $controller])->row_array();
    //         $menu_id = $queryMenu ? $queryMenu['id'] : 0;

    //         $querySubMenu = $ci->db->query("SELECT * FROM `user_sub_menu` WHERE url = '$url' AND menu_id = '$menu_id'")->row_array();
    //         $submenu_id = $querySubMenu ? $querySubMenu['id'] : 0;

    //         $userAccessMenu = $ci->db->get_where('user_access_menu', [
    //             'role_id' => $role_id,
    //             'menu_id' => $menu_id
    //         ]);

    //         $userAccessSubMenu = $ci->db->get_where('user_access_submenu', [
    //             'role_id' => $role_id,
    //             'menu_id' => $menu_id,
    //             'submenu_id' => $submenu_id
    //         ]);

    //         // echo '<br>';
    //         // echo '<br>';
    //         // echo $menu_id;
    //         // echo '<br>';
    //         // echo $submenu_id;
    //         // echo '<br>';
    //         // die;

    //         if ($userAccessMenu->num_rows() < 1 || $userAccessSubMenu->num_rows() < 1) {
    //             if($menu_id != 5){
    //                 redirect('auth/blocked');
    //             }
    //         }
    //     }
    // }

    // function is_allowed_submenu($submenu) 
    // {
    //     $ci = get_instance();
    //     $role_id = $ci->session->userdata('role_id');
    //     $menu = $ci->uri->segment(1);

    //     $queryMenu = $ci->db->get_where('user_menu', ['menu' => $menu])->row_array();
    //     $menu_id = $queryMenu['id'];
        
    //     $querySubMenu = $ci->db->get_where('user_sub_menu', [
    //         'title' => $submenu,
    //         'menu_id' => $menu_id
    //         ])->row_array();
    //     $submenu_id = $querySubMenu['id'];

    //     $userAccess = $ci->db->get_where('user_access_submenu', [
    //         'role_id' => $role_id,
    //         'menu_id' => $menu_id,
    //         'submenu_id' => $submenu_id
    //     ]);
        
    //     if($userAccess->num_rows() == 0) {
    //         redirect('auth/blocked');
    //     }
    // }

    // function check_access($role_id, $menu_id)
    // {
    //     $ci = get_instance();

    //     $ci->db->where('role_id', $role_id);
    //     $ci->db->where('menu_id', $menu_id);
    //     $result = $ci->db->get('user_access_menu');

    //     if ($result->num_rows() > 0) {
    //         return "checked='checked'";
    //     }
    // }

    // function check_access_submenu($role_id, $menu_id, $submenu_id)
    // {
    //     $ci = get_instance();

    //     $ci->db->where('role_id', $role_id);
    //     $ci->db->where('menu_id', $menu_id);
    //     $ci->db->where('submenu_id', $submenu_id); 
    //     $result = $ci->db->get('user_access_submenu');

    //     if ($result->num_rows() > 0) {
    //         return "checked='checked'";
    //     }
    // }