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
}