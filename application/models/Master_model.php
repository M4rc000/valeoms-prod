<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_model extends CI_Model {
    public function getListMaterial(){
        return $this->db->get('material_list')->result_array();
    }

    public function getBom(){
        return $this->db->get('bom')->result_array();
    }

    public function insertData($table,$Data){
        return $this->db->insert($table,$Data);
    }

    public function updateData($table, $id, $Data){
        $this->db->where('id',$id);  
        $this->db->update($table, $Data);
    } 

    public function deleteData($table, $id){
        $this->db->where('id',$id);
        $this->db->delete($table);
    }
}