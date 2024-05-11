<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_model extends CI_Model {
    public function getListMaterial(){
        return $this->db->get('material_list')->result_array();
    }

    public function getBom(){
        return $this->db->get('bom')->result_array();
    }
}