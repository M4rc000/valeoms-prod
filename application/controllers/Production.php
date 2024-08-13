<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');
class Production extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->library('form_validation');
        $this->load->model('Production_model', 'PModel');
        $this->load->model('Quality_model', 'QModel');
    }
	
	public function index()
	{
        $data['title'] = 'Material Request';

        $data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $data['name'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        

        $data['boms'] = $this->PModel->getAllBoms();
        $data['materials'] = $this->PModel->getMaterialList();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);   
        $this->load->view('templates/sidebar', $data);   
        $this->load->view('production/material_request', $data);
        $this->load->view('templates/footer');
	}
    
    public function edit_production_plan($production_plan){
        $data['title'] = 'Material Request';

        $data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $data['name'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        
        $data['production_plans'] = $this->PModel->getProductionPlanById($production_plan)->result_array();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);   
        $this->load->view('templates/sidebar', $data);   
        $this->load->view('production/edit-production-plan', $data);
        $this->load->view('templates/footer');
    }

        function getProduct(){
            $productID = $this->input->post('productID');
            $result = $this->db->query("SELECT Id_fg, Fg_desc FROM bom WHERE Id_fg = '$productID'")->result_array();
        
            echo json_encode($result);
        }

        function getMaterial(){
            $materialID = htmlspecialchars($this->input->post('material_id'));
            $material_list = $this->db->query("SELECT * FROM material_list WHERE Id_material = '$materialID' AND is_active = 1")->result_array();
            $production_plans = $this->db->query("SELECT * FROM production_plan")->result_array();
            $result = [
                'result_material' => $material_list,
                'result_production_plan' => $production_plans
            ];
        
            echo json_encode($result);
        }

        function getProductData(){
            $productID = $this->input->post('productId');
            $productDesc = $this->input->post('productDescription');
            $product_plan_qty = $this->input->post('qty');
            $user = $this->input->post('user');
 
            // Get the last production plan
            $production_plan = $this->PModel->getLastProductionPlan();
        
            // Data for Table production_plan
            $Data = [
                'Production_plan' => $production_plan,
                'Id_fg' => $productID,
                'Fg_desc' => $productDesc,
                'Production_plan_qty' => $product_plan_qty,
                'status' => 'NEW',
                'Crtdt' => date('Y-m-d H:i'),
                'Crtby' => $user,
                'Upddt' => date('Y-m-d H:i'),
                'Updby' => $user
            ];
        
            // Insert data into production_plan table
            $this->PModel->insertData('production_plan', $Data);
        
            // Retrieve BOM data
            $result = $this->db->query("SELECT * FROM bom WHERE Id_fg = '$productID'")->result_array();
        
            // Prepare and insert data into production_plan_detail table
            foreach ($result as $row) {
                $detailData = [
                    'Production_plan' => $production_plan,
                    'Id_material' => $row['Id_material'],
                    'Material_desc' => $row['Material_desc'],
                    'Material_need' => $row['Qty'] * $product_plan_qty,
                    'Uom' => $row['Uom'],
                    'status' => 0, 
                    'Crtdt' => date('Y-m-d H:i'),
                    'Crtby' => $user,
                    'Upddt' => date('Y-m-d H:i'),
                    'Updby' => $user
                ];
        
                // Insert into production_plan_detail table
                $this->PModel->insertData('production_plan_detail', $detailData);
            }

            redirect('production/edit_production_plan/' . $production_plan);
        }

        public function editProductionPlan(){
            $production_plan = $this->input->post('production_plan');
            $Production_plan_detail_id = $this->input->post('prod_plan_id');
            $id_material = $this->input->post('materials_id');

            $data = [
                'Qty' => $this->input->post('qty'),
                'Upddt' => date('Y-m-d H:i:s'), 
                'Updby' => $this->input->post('user')
            ];
            
            // Check if the record already exists
            $existing_request = $this->PModel->getRequest($production_plan, $Production_plan_detail_id);
        
            if ($existing_request) {
                $this->PModel->updateDataPP('production_request', $data, ['Production_plan' => $production_plan, 'Production_plan_detail_id' => $Production_plan_detail_id]);
            } else {
                $data = array_merge($data, [
                    'Production_plan_detail_id' => $Production_plan_detail_id,
                    'Id_request' => $this->PModel->getLastReqNo(),
                    'Id_material' => $id_material,
                    'Material_desc' => $this->input->post('material_desc'),
                    'Sloc' => '',
                    'id_box' => '',
                    'Production_plan' => $production_plan,
                    'Crtdt' => date('Y-m-d H:i:s'), 
                    'Crtby' => $this->input->post('user')
                ]);
                $this->PModel->insertData('production_request', $data);
            }
        
            $this->session->set_flashdata('success', 'Material Qty have been updated');
            redirect('production/edit_production_plan/'. $production_plan);
        }        
        
        function getSlocStorage(){
            $materialId = $this->input->post('materialId');
            $result = $this->db->query("SELECT 
                    b.no_box, 
                    b.id_box, 
                    b.weight, 
                    b.sloc, 
                    bd.id as list_storage_id, 
                    bd.product_id, 
                    bd.material_desc, 
                    bd.total_qty, 
                    bd.total_qty_real, 
                    bd.uom,
                    s.SLoc
                FROM 
                    box b
                LEFT JOIN 
                    list_storage bd ON b.id_box = bd.id_box
                LEFT JOIN 
                    storage s ON s.Id_storage = b.sloc
                WHERE 
                    bd.product_id = '$materialId'
                    AND b.sloc BETWEEN 1 AND 456")->result_array();
            echo json_encode($result);
        }

        function getCalculateMaterial(){
            $materialID = $this->input->post('material_id');
            $materialDesc = $this->input->post('material_desc');
            $materialNeed = $this->input->post('material_need');
            $production_plan = $this->input->post('production_plan');

            $Data = [
                'Id_request' => $this->PModel->getLastReqNo(),
                'Id_material' => $materialID,
                'Material_desc' => $materialDesc,
                'Qty' => $materialNeed,
                'Sloc' => '',
                'id_box' => '',
                'Production_plan' => $production_plan,
                'Crtdt' => date('Y-m-d H:i:s'), 
                'Crtby' => $this->input->post('user')
            ];

            $this->PModel->insertData('production_request', $Data);
            $result ='success';
            
            echo json_encode($result);
        }

        // public function updateBoxQuantity(){
        //     $response = array('success' => false);

        //     $materialData = $this->input->post('materialData');
        //     $id_list_storage = $this->input->post('id_list_storage');
            
        //     foreach ($materialData as $data) {
        //         $DataRequest = [
        //             'Id_request' => $this->PModel->getLastReqNo(),
        //             'Id_material' => $data['id_material'],
        //             'Material_desc' => $data['material_desc'],
        //             'Sloc' => $data['sloc'],
        //             'id_box' => $data['box_no'],
        //             'Production_plan' => $this->input->post('Production_plan'),
        //             'Qty' => $data['qty_unpack'],
        //             'Crtdt' => date('Y-m-d H:i:s'), 
        //             'Crtby' => $this->input->post('user'),
        //             'Upddt' => date('Y-m-d H:i:s'), 
        //             'Updby' => $this->input->post('user'),
        //         ];

        //         $this->QModel->insertData('production_request', $DataRequest);
        //     }

        //     // Get the input data from AJAX request
        //     $id_box = $this->input->post('id_box');
        //     $total_qty_real = $this->input->post('total_qty_real');

        //     // Validate the inputs
        //     if ($id_box && $total_qty_real != null || $id_box && $total_qty_real != '') {
        //         // Update the quantity in the database
        //         $update_result = $this->QModel->updateBoxQuantity($id_list_storage, $total_qty_real);

        //         if ($update_result) {
        //             $response['success'] = true;
        //         }
        //     }

        //     echo json_encode($response);
        // }

        function getBox()
        {
            $boxID = $this->input->post('boxID');
            $result = $this->db->query("SELECT 
                    b.no_box, 
                    b.weight, 
                    b.sloc, 
                    bd.product_id, 
                    bd.material_desc, 
                    bd.total_qty, 
                    bd.uom
                FROM box b
                LEFT JOIN list_storage bd ON b.id_box = bd.id_box
                WHERE b.no_box = '$boxID'
            ")->result_array();
        
            echo json_encode($result);
        }


    public function kanban_box(){
        $data['title'] = 'Kanban Box';

        $data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $data['name'] = $this->db->get_where('user', ['name' => $this->session->userdata('name')])->row_array();
        

        $data['kanbanlist'] = $this->PModel->getKanbanList();
        $data['kanban'] = $this->PModel->getLastKanbanID();
        $data['material_list'] = $this->PModel->getMaterialList();
        $data['production_plans'] = $this->db->query("SELECT * FROM production_plan")->result_array();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);   
        $this->load->view('templates/sidebar', $data);   
        $this->load->view('production/kanban_box', $data);
        $this->load->view('templates/footer');
    }

        // ADD KANBAN BOX
        function AddKanbanBox(){
            $materialID = $this->input->post('material_id');
            $production_plan = $this->input->post('production_planning');
            $ProductID = $this->db->query("SELECT * FROM production_plan WHERE Production_plan = '$production_plan'")->result_array();

            $Data = array(
                'Id_kanban_box' => $this->input->post('kanbanBox_id'),
                'Id_material' => $materialID,
                'Material_desc' => $this->input->post('material_desc'),
                'Material_qty' => $this->input->post('qty'),
                'Product_plan' => $production_plan,
                'product_id' => $ProductID[0]['Id_fg'],
                'crtdt' => date('Y-d-m H:i'),
                'crtby' => $this->input->post('user'),
                'upddt' => date('Y-d-m H:i'),
                'updby' => $this->input->post('user')
            );

            $this->session->set_flashdata('kanban_data', $Data);
            $Result = $this->PModel->insertData('kanban_box', $Data);

            if ($Result) {
                // Success
                $this->session->set_flashdata('ADD', '
                    <div class="alert alert-success alert-dismissible fade show" role="alert" style="width: 40%;">
                        <i class="bi bi-check-circle me-1"></i> New Kanban Box successfully added
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                ');

            } else {
                // Failure
                $this->session->set_flashdata('ERROR', '
                    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="width: 40%;">
                        <i class="bi bi-exclamation-circle me-1"></i> Failed to add New Kanban Box
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                ');
            }
            redirect('production/kanban_box');
        }

        // EDIT KANBAN BOX
        function EditKanbanBox(){
            $id = $this->input->post('id');
            $Data = [
                'Id_material' => $this->input->post('material_id'),
                'Material_desc' => $this->input->post('material_desc'),
                'Material_qty' => $this->input->post('material_qty'),
                'Product_plan' => $this->input->post('product_plan'),
                'product_id' => $this->input->post('product_id'),
                'Upddt' => date('Y-m-d H:i:s'),
                'Updby' => $this->input->post('user')
            ];

            $this->PModel->updateDataKanban('kanban_box', $id, $Data);
            $update = $this->db->affected_rows();

            // CHECK IF SUCCESS UPDATE OR NO
            if ($update == 1) {
                $this->session->set_flashdata('success_edit_kanban_box', 'Kanban Box <b>' . $id . '</b> has been updated');
            } else {
                $this->session->set_flashdata('failed_edit_kanban_box', 'Failed to update Kanban Box <b>' . $id . '</b>');
            }
            redirect('production/kanban_box');
        }

        // DELETE KANBAN BOX
        function DeleteKanbanBox(){
            $id = $this->input->post('id');
            
            $this->db->query("DELETE FROM kanban_box WHERE id_kanban_box = '$id'");
            $delete = $this->db->affected_rows();

            if ($delete == 1) {
                $this->session->set_flashdata('success_delete_kanban_box', 'Kanban Box <b>' . $id . '</b> has been deleted');
            } else{
                $this->session->set_flashdata('failed_delete_kanban_box', 'Failed to delete Kanban Box <b>' . $id . '</b>');
            }
            redirect('production/kanban_box');
        }

        function getProductIdByProductPlan(){
            $product_plan = $this->input->post('product_plan');
            $result = $this->db->query("SELECT * FROM production_plan WHERE production_plan = '$product_plan'")->result_array();
        
            echo json_encode($result);   
        }

        function getMaterialById(){
            $material_id = $this->input->post('material_id');
            $result = $this->db->query("SELECT * FROM material_list WHERE Id_material = '$material_id'")->result_array();
        
            echo json_encode($result);   
        }

        // PRINT KANBAN BOX
        public function print_kanban() {
            // Load user data
            $data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
            $data['name'] = $this->db->get_where('user', ['name' => $this->session->userdata('name')])->row_array();
            $data['title'] = 'Kitting';

            // Retrieve data from $_GET
            $data['materialID'] = $this->input->get('materialID');
            $data['materialDesc'] = $this->input->get('materialDesc');
            $data['materialQty'] = $this->input->get('materialQty');
            $data['proPlan'] = $this->input->get('proPlan');
        }

        function getMaterialList(){
            $materialID = $this->input->post('materialID');
            $result = $this->db->query("SELECT * FROM material_list WHERE Id_material = '$materialID'")->result_array();
        
            echo json_encode($result);
        }
    
        public function getKanbanImage(){
            $id_kanban_box = $this->input->post('id_kanban');
            $result = $this->db->query("SELECT * FROM kanban_box WHERE Id_kanban_box = '$id_kanban_box'")->result_array();
        
            echo json_encode($result);
        }

    public function material_return()
	{
        $data['title'] = 'Material Return';

        $data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $data['name'] = $this->db->get_where('user', ['name' => $this->session->userdata('name')])->row_array();
        
        $data['material_list'] = $this->PModel->getMaterialList();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/navbar', $data);   
        $this->load->view('templates/sidebar', $data);   
        $this->load->view('production/material_return', $data);
        $this->load->view('templates/footer');
	}

        function getslocbasedweight(){
            $weight = $this->input->post('weight');
            $result = $this->PModel->getsloc($weight);

            echo json_encode($result);
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
            $id_return = $this->PModel->getLastIdReturn();

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
            $this->PModel->insertData('return_warehouse', $DataReturnWarehouse);
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
                    $this->PModel->insertData('return_warehouse_detail', $DataReturnWarehouseDetail);
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
            $id_return = $this->PModel->getLastIdReturn();

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
            $this->PModel->insertData('return_warehouse', $DataReturnWarehouse);
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
                    $this->PModel->insertData('return_warehouse_detail', $DataReturnWarehouseDetail);
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