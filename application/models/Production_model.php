<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Production_model extends CI_Model {
    public function getMaterialDesc($materialID) {
        $this->db->select('Material_desc');
        $this->db->where('Id_material', $materialID);
        $this->db->where('is_active', 1);
        $this->db->limit(1);
        $query = $this->db->get('material_list');
        return $query->result_array();
    }

    public function insertData($table,$Data){
        return $this->db->insert($table,$Data);
    }
    
    public function getKanbanList(){
        return $this->db->get('kanban_box')->result_array();
    }
}