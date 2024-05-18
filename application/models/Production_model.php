<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Production_model extends CI_Model {
    public function getBomDesc($productID) {
        $this->db->select('Fg_desc');
        $this->db->where('Id_fg', $productID);
        $this->db->where('is_active', 1);
        $this->db->limit(1);
        $query = $this->db->get('bom');
        return $query->result_array();
    }

    public function insertData($table,$Data){
        return $this->db->insert($table,$Data);
    }
    
    public function getKanbanList(){
        return $this->db->get('kanban_box')->result_array();
    }
}