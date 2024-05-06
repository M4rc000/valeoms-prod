<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Warehouse_model extends CI_Model {
    public function getAllUsers(){
       return $this->db->get('user')->result_array();
    }
}