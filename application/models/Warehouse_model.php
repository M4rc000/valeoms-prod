<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Warehouse_model extends CI_Model {
    public function getAllUsers(){
       return $this->db->get('user')->result_array();
    }

    public function insertData($table,$Data){
        return $this->db->insert($table,$Data);
    }

    public function getMaterialDetails($id_material) {
        $query = $this->db->get_where('material_list', array('id_material' => $id_material));
        return $query->result_array();
    }
    
    public function getReceivingMaterials() {
        return $this->db->get('receiving_material')->result_array();
    }

    
}