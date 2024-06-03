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
            $prefix = substr($lastKanbanID, 0, 3); // Assuming 'KBA' is always the prefix
            $numericPart = substr($lastKanbanID, 3);
        
            // Increment the numeric part
            $incrementedNumericPart = (int)$numericPart + 1;
        
            // If the numeric part reaches 10000000, reset to 1 and increment the prefix
            if ($incrementedNumericPart >= 10000000) {
                $incrementedNumericPart = 1;
                
                // Increment the last character of the prefix
                $lastChar = substr($prefix, -1);
                $secondChar = substr($prefix, -2, 1);
                $firstChar = substr($prefix, -3, 1);
                
                // Increment the last character, if it is 'Z', reset to 'A' and increment the second last character
                if ($lastChar === 'Z') {
                    $lastChar = 'A';
                    $secondChar++;
        
                    // If the second character is 'Z', reset to 'A' and increment the first character
                    if ($secondChar === 'Z' + 1) {
                        $secondChar = 'A';
                        $firstChar++;
                    }
                } else {
                    $lastChar++;
                }
                
                // Combine characters to form the new prefix
                $prefix = $firstChar . $secondChar . $lastChar;
            }
        
            // Format the incremented numeric part
            $formattedNumericPart = str_pad($incrementedNumericPart, strlen($numericPart), '0', STR_PAD_LEFT);
        
            // Return the next Kanban ID
            return $prefix . $formattedNumericPart;
        } else {
            // Handle the case when the table is empty
            return 'KBA0000001';
        }        
    }

    public function getMaterialList(){
        return $this->db->get('material_list')->result_array();
    }
}