<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');
class Quality extends CI_Controller {
    public function __construct(){
        parent::__construct();
        is_logged_in();
        $this->load->library('form_validation');
        $this->load->model('Quality_model', 'QModel');
    }
	
	public function index(){
        $data['title'] = 'Material Request';

        $data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $data['name'] = $this->db->get_where('user', ['name' => $this->session->userdata('name')])->row_array();
        
        $data['materials'] = $this->QModel->getAllMaterials(); 

        $this->load->view('templates/header' , $data);
        $this->load->view('templates/navbar', $data);   
        $this->load->view('templates/sidebar', $data);   
        $this->load->view('quality/material_request', $data);
        $this->load->view('templates/footer');
	}  
	
        function getMaterial(){
            $materialID = htmlspecialchars($this->input->post('material_id'));
            $result = $this->db->query("SELECT * FROM material_list WHERE Id_material = '$materialID' AND is_active = 1")->result_array();
        
            echo json_encode($result);
        }

        function getCalculateMaterial(){
            $materialID = $this->input->post('material_id');
            $materialDesc = $this->input->post('material_desc');
            $materialNeed = $this->input->post('material_need');
            $materialUom = $this->input->post('material_uom');

            $Data = [
                'Id_request' => $this->QModel->getLastIdRequest(),
                'Id_material' => $materialID,
                'Material_desc' => $materialDesc,
                'Material_need' => $materialNeed,
                'Uom' => $materialUom,
                'status' => 1, // 1: PENDING 0: APPROVED
                'Crtdt' => date('Y-m-d H:i:s'), 
                'Crtby' => $this->input->post('user'),
                'Upddt' => date('Y-m-d H:i:s'), 
                'Updby' => $this->input->post('user')
            ];

            $this->QModel->insertData('quality_request', $Data);
            $query = $this->db->affected_rows();
            
            if($query == 1){
                // RECORD QUALITY MATERIAL REQUEST LOG
                $query_log = $this->db->last_query();
                $log_data = [
                    'affected_table' => 'quality_request',
                    'queries' => $query_log,
                    'Crtdt' => date('Y-m-d H:i:s'),
                    'Crtby' => $this->input->post('user')
                ];    
                $this->db->insert('quality_material_request_log', $log_data);
                $result ='success';
            }
            else{
                $result ='failed';
            }
            
            echo json_encode($result);
        }

        public function updateBoxQuantity(){
            $response = array('success' => false);

            $materialData = $this->input->post('materialData');
            $id_list_storage = $this->input->post('id_list_storage');
            
            foreach ($materialData as $data) {
                $DataRequest = [
                    'Id_request' => $this->input->post('Id_request'),
                    'sloc' => $data['sloc'],
                    'id_box' => $data['box_no'],
                    'qty_unpack' => $data['qty_unpack'],
                    'Crtdt' => date('Y-m-d H:i:s'), 
                    'Crtby' => $this->input->post('user'),
                    'Upddt' => date('Y-m-d H:i:s'), 
                    'Updby' => $this->input->post('user'),
                ];

                $this->QModel->insertData('quality_request_detail', $DataRequest);
                
                // RECORD QUALITY MATERIAL REQUEST LOG
                $query_log = $this->db->last_query();
                $log_data = [
                    'affected_table' => 'quality_request_detail',
                    'queries' => $query_log,
                    'Crtdt' => date('Y-m-d H:i:s'),
                    'Crtby' => $this->input->post('user')
                ];    
                $this->db->insert('quality_material_request_log', $log_data);
            }

            // Get the input data from AJAX request
            $id_box = $this->input->post('id_box');
            $total_qty_real = $this->input->post('total_qty_real');

            // Validate the inputs
            if ($id_box && $total_qty_real != null || $total_qty_real != '') {
                // Update the quantity in the database
                $update_result = $this->QModel->updateBoxQuantity($id_list_storage, $total_qty_real);

                if ($update_result) {
                    $response['success'] = true;
                }
            }

            echo json_encode($response);
        }

    public function material_return(){
        $data['title'] = 'Material Return';

        $data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $data['name'] = $this->db->get_where('user', ['name' => $this->session->userdata('name')])->row_array();

        $data['material_list'] = $this->QModel->getMaterialList();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);   
        $this->load->view('templates/sidebar', $data);   
        $this->load->view('quality/material_return', $data);
        $this->load->view('templates/footer');
	}  
    
        function getMaterialDesc(){
            $materialID = htmlspecialchars($this->input->post('materialID'));
            $result = $this->db->query("SELECT Material_desc, Material_type, Uom FROM material_list WHERE Id_material = '$materialID' AND is_active = 1")->result_array();
        
            echo json_encode($result);
        }

        function AddHighRack(){
            $user = $this->input->post('user');
            $materialData = $this->input->post('materialData');
            $weight = $this->input->post('weight');
            $id_return = $this->QModel->getLastIdReturn();

            $DataReturnWarehouse = [
                'id_return' => $id_return,
                'box_type' => 'HIGH',
                'box_weight' => $weight,
                'status' => 1, // 1: PENDING, 0: APPROVED
                'Crtdt' => date('Y-m-d H:i:s'),
                'Crtby' => $user,
                'Upddt' => date('Y-m-d H:i:s'),
                'Updby' => $user
            ];
            $this->QModel->insertData('return_warehouse', $DataReturnWarehouse);
            $queryDataReturnWarehouse = $this->db->affected_rows();

            // JIKA TABLE RETURN WAREHOUSE BERHASIL DI INSERT
            if($queryDataReturnWarehouse > 0){
                $queryDataReturnWarehouseDetail = 0;
                foreach($materialData as $md){
                    $DataReturnWarehouseDetail = [
                        'id_return' => $id_return,
                        'Id_material' => $md['material_id'], 
                        'Material_desc' => $md['material_desc'],
                        'Material_qty' => $md['material_qty'],
                        'Material_uom' => $md['material_uom'],
                        'Crtdt' => date('Y-m-d H:i:s'),
                        'Crtdt' => $user,
                        'Upddt' => date('Y-m-d H:i:s'),
                        'Updby' => $user
                    ];
                    $this->QModel->insertData('return_warehouse_detail', $DataReturnWarehouseDetail);
                    $checkinsert = $this->db->affected_rows();
                    
                    
                    if($checkinsert > 0){
                        $queryDataReturnWarehouseDetail+=1;
                    }
                }

                // JIKA TABLE RETURN WAREHOUSE DETAIL BERHASIL DI INSERT
                if($queryDataReturnWarehouseDetail > 0){
                    $result = 2;
                }
                // JIKA GAGAL INSERT TABLE RETURN WAREHOUSE DETAIL
                else{
                    $result = 1; 
                }
            }
            // JIKA GAGAL INSERT TABLE RETURN WAREHOUSE
            else{
                $result = 0;
            }

            
            echo json_encode($result);
        }
    
        function AddMediumRack(){
            $user = $this->input->post('user');
            $materialData = $this->input->post('materialData');
            $weight = $this->input->post('weight');
            $id_return = $this->QModel->getLastIdReturn();

            $DataReturnWarehouse = [
                'id_return' => $id_return,
                'box_type' => 'MEDIUM',
                'box_weight' => $weight,
                'status' => 1, // 1: PENDING, 0: APPROVED
                'Crtdt' => date('Y-m-d H:i:s'),
                'Crtby' => $user,
                'Upddt' => date('Y-m-d H:i:s'),
                'Updby' => $user
            ];
            $this->QModel->insertData('return_warehouse', $DataReturnWarehouse);
            $queryDataReturnWarehouse = $this->db->affected_rows();

            // JIKA TABLE RETURN WAREHOUSE BERHASIL DI INSERT
            if($queryDataReturnWarehouse > 0){
                $queryDataReturnWarehouseDetail = 0;
                foreach($materialData as $md){
                    $DataReturnWarehouseDetail = [
                        'id_return' => $id_return,
                        'Id_material' => $md['material_id'], 
                        'Material_desc' => $md['material_desc'],
                        'Material_qty' => $md['material_qty'],
                        'Material_uom' => $md['material_uom'],
                        'Crtdt' => date('Y-m-d H:i:s'),
                        'Crtdt' => $user,
                        'Upddt' => date('Y-m-d H:i:s'),
                        'Updby' => $user
                    ];
                    $this->QModel->insertData('return_warehouse_detail', $DataReturnWarehouseDetail);
                    $checkinsert = $this->db->affected_rows();
                    
                    
                    if($checkinsert > 0){
                        $queryDataReturnWarehouseDetail+=1;
                    }
                }

                // JIKA TABLE RETURN WAREHOUSE DETAIL BERHASIL DI INSERT
                if($queryDataReturnWarehouseDetail > 0){
                    $result = 2;
                }
                // JIKA GAGAL INSERT TABLE RETURN WAREHOUSE DETAIL
                else{
                    $result = 1; 
                }
            }
            // JIKA GAGAL INSERT TABLE RETURN WAREHOUSE
            else{
                $result = 0;
            }

            
            echo json_encode($result);
        }
}