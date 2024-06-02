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

    public function getLastKanbanID() {
        $this->db->select('id_kanban_box');
        $this->db->from('kanban_box');
        $this->db->order_by('id_kanban_box', 'DESC');
        $this->db->limit(1);
        
        $query = $this->db->get();
        
        if ($query->num_rows() > 0) {
            // Return the last ID
            $lastKanbanID = $query->row()->id_kanban_box;
            $prefix = substr($lastKanbanID, 0, 2); // Assuming 'KB' is always the prefix
            $numericPart = substr($lastKanbanID, 2);

            // Increment the numeric part
            $incrementedNumericPart = str_pad((int)$numericPart + 1, strlen($numericPart), '0', STR_PAD_LEFT);

            // Return the next Kanban ID
            return $prefix . $incrementedNumericPart;
        } else {
            // Handle the case when the table is empty
            return 'KB0001';
        }
    }

    public function getMaterialList(){
        return $this->db->get('material_list')->result_array();
    }
}