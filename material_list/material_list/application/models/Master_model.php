<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_model extends CI_Model {
    public function getListMaterial(){
        $this->db->where('is_active', 1); 
        return $this->db->get('material_list')->result_array();
    }

    public function getMaterials(){
        $this->db->select('Id_material, Material_desc, Material_type, Uom, Family');
        $this->db->where('is_active', 1); 
        return $this->db->get('material_list')->result_array(); 
    }

    public function getBom(){
        $this->db->where('is_active', 1); 
        return $this->db->get('bom')->result_array(); 
    }

    public function getBomDistinct() {
        $this->db->distinct();
        $this->db->select('Id_fg');
        $this->db->where('is_active', 1); 
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

    // READ DATA PRINT
    public function material_list_print() {
        $this->db->select('Id_material, Material_desc, Material_type, Uom, Family');
        $this->db->from('material_list');
        $this->db->where('is_active', 1);
        $query = $this->db->get();
        return $query->result();
    }
}