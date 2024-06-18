<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Quality_model extends CI_Model {
    public function generateFormattedBoxNumber(){
		$last_box = $this->db->order_by('id_box', 'DESC')->get('box')->row();

		if (!$last_box) {
			return 'CKA00001';
		}

		$prefix = substr($last_box->no_box, 0, 2);
		$last_char = substr($last_box->no_box, 2, 1);
		$last_number = substr($last_box->no_box, 3);

		$new_number = (int) $last_number + 1;

		if ($new_number > 99999) {
			$new_number = 1;
			$last_char++;
		}

		$formatted_box_number = $prefix . $last_char . str_pad($new_number, 6, '0', STR_PAD_LEFT);

		return $formatted_box_number;
	}

    public function getsloc($weight) {
        return $this->db->query("SELECT * FROM `storage` WHERE $weight BETWEEN min_loads AND max_loads AND Rack IN ('A', 'B', 'C', 'D', 'E', 'F', 'Gangway A-B', 'Gangway C-D', 'Gangway E-F')")->result_array();
    }

    public function insertData($table,$Data){
        return $this->db->insert($table,$Data);
    }

	public function getAllMaterials(){
		return $this->db->get('material_list')->result_array();
	}

	public function getLastIdReturn() {
        $this->db->select('Id_request');
        $this->db->from('quality_request');
        $this->db->order_by('Id_request', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();
        
        // Check if there are any rows returned
        if ($query->num_rows() == 0) {
            return 'QRA0000001';
        } else {
            // Get the last Id_request
            $row = $query->row();
            $last_id = $row->Id_request;
            
            // Extract the prefix and the number part
            $prefix = substr($last_id, 0, 3);
            $number = (int) substr($last_id, 3);

            // Increment the number part
            $number += 1;

            // Check if the number part needs to reset and prefix to increment
            if ($number > 9999999) {
                $prefix++;
                $number = 1;
            }

            // Format the new Id_return
            $new_id = $prefix . str_pad($number, 7, '0', STR_PAD_LEFT);

            return $new_id;
        }
    }
}