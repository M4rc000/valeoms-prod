<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends CI_Model {

   // CREATE DATA
   public function insertData($table,$Data){
      return $this->db->insert($table,$Data);
   }
    
   // READ DATA
   public function getListStorage(){
    return $this->db->get('storage')->result_array();
   }

   public function getAllUsers(){
       return $this->db->get_where('user')->result_array();
   }
   
   public function getMenuAccess($role_id){
       return $this->db->query("SELECT user_access_menu.id, user_role.id as role_id, user_role.role, user_menu.menu
            FROM `user_access_menu`
            LEFT JOIN `user_role` ON user_role.id = user_access_menu.role_id
            LEFT JOIN `user_menu` ON user_menu.id = user_access_menu.menu_id
            WHERE user_access_menu.role_id = '$role_id'
            ORDER BY role_id")->result_array();
   }

   public function getSubMenuAccess($role_id){
       return $this->db->query("SELECT user_access_submenu.id, user_role.id as role_id, user_role.role, user_menu.menu, user_sub_menu.title
            FROM `user_access_submenu`
            LEFT JOIN `user_role` ON user_role.id = user_access_submenu.role_id
            LEFT JOIN `user_menu` ON user_menu.id = user_access_submenu.menu_id
            LEFT JOIN `user_sub_menu` ON user_sub_menu.id = user_access_submenu.submenu_id
            WHERE user_access_submenu.role_id = '$role_id'
            ORDER BY role_id")->result_array();
   }
   
   public function getMenSub(){
       return $this->db->query('SELECT user_sub_menu.id AS submenu_id, user_sub_menu.title, user_menu.id AS menu_id, user_menu.menu
            FROM `user_sub_menu`
            LEFT JOIN `user_menu` ON user_sub_menu.menu_id = user_menu.id
            WHERE is_active = 1;')->result_array();
   }

   public function getUsers() {
        $months = range(1, 12);
        $year = date('Y');
        foreach ($months as $month) {
            $start_date = date('Y-m-01', strtotime("$year-$month-01"));
            $end_date = date('Y-m-t', strtotime("$year-$month-01"));

            $this->db->where("date_joined BETWEEN '$start_date' AND '$end_date'");
            $count = $this->db->count_all_results('user');    
            $result[] = $count;
        }
        return $result;
   }

    public function get_menu_id_by_submenu_id($submenu_id) {
        $query = $this->db->get_where('user_sub_menu', ['id' => $submenu_id]);
        if ($query->num_rows() > 0) {
            return $query->row()->menu_id;
        }
        return null;
    }

   public function getAllRoles(){
       return $this->db->get('user_role')->result_array();
   }

   public function getAllMenu(){
       return $this->db->get('user_menu')->result_array();
   }
    
   public function getAllSubMenu(){
       return $this->db->get('user_sub_menu')->result_array();
    }

   public function getAllBooks(){
       return $this->db->get('books')->result_array();
    }

   // UPDATE DATA
   public function updateData($table, $id, $Data){
      $this->db->where('id',$id);  
      $this->db->update($table, $Data);
    } 

   // DELETE DATA
   public function deleteData($table, $id){
        $this->db->where('id',$id);
        $this->db->delete($table);
    }

  // MESSAGE
    public function displayAlert() {
        if (isset($_SESSION['message'])) {
            $message = $_SESSION['message'];
            $alertClass = '';
            $alertText = '';

            switch ($message) {
                case 'delete':
                    $alertClass = 'alert-success';
                    $alertText = 'The role has been deleted!';
                    break;
                case 'error':
                    $alertClass = 'alert-danger';
                    $alertText = 'Action has failed';
                    break;
                // Add more cases as needed

                default:
                    // Default case if 'message' doesn't match any expected values
                    return;
            }

            ?>
            <div class="alert <?php echo $alertClass; ?> alert-dismissible fade show" role="alert">
                <?php echo $alertText; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <?php unset($_SESSION['message']); ?>
            </div>
            <?php
        }
    }

    public function countAllBoxes(){
        return $this->db->query("SELECT a.*, b.SLoc as sloc_name 
			FROM `box` a 
			LEFT JOIN storage b ON a.sloc = b.Id_storage")->num_rows();
    }


    public function getBoxes($limit, $start){
        $this->db->select('a.*, b.SLoc as sloc_name');
        $this->db->from('box a');
        $this->db->join('storage b', 'a.sloc = b.Id_storage', 'left');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getLastRoleId(){
        return $this->db->query("SELECT * FROM `user_role` ORDER BY `id` DESC LIMIT 1")->result_array();
    }

    public function getBoxData($box_type, $year) {
        // Initialize an array with 12 entries (one for each month) and set the count to 0.
        $boxData = array_fill(0, 12, 0);

        // Query to get the number of boxes by month for the given box_type and year.
        $this->db->select('MONTH(Crtdt) as month, COUNT(*) as count');
        $this->db->from('box');
        $this->db->where('box_type', $box_type);
        $this->db->where('YEAR(Crtdt)', $year);
        $this->db->group_by('MONTH(Crtdt)');
        $query = $this->db->get();

        // Fetch the results and map them to the corresponding month in the boxData array.
        foreach ($query->result() as $row) {
            // Subtract 1 from the month because array indexes start at 0 (Jan = 0, Feb = 1, etc.).
            $boxData[$row->month - 1] = (int) $row->count;
        }

        return $boxData;
    }
    
    public function getKanbanData($year) {
        // Initialize an array with 12 entries (one for each month) and set the count to 0.
        $kanbanData = array_fill(0, 12, 0);

        // Query to get the number of boxes by month for the given box_type and year.
        $this->db->select('MONTH(Crtdt) as month, COUNT(*) as count');
        $this->db->from('kanban_box');
        $this->db->where('YEAR(Crtdt)', $year);
        $this->db->group_by('MONTH(Crtdt)');
        $query = $this->db->get();

        // Fetch the results and map them to the corresponding month in the kanbanData array.
        foreach ($query->result() as $row) {
            // Subtract 1 from the month because array indexes start at 0 (Jan = 0, Feb = 1, etc.).
            $kanbanData[$row->month - 1] = (int) $row->count;
        }

        return $kanbanData;
    }

    public function getProductionRequestData($year) {
        // Initialize an array with 12 entries (one for each month) and set the count to 0.
        $productionRequestData = array_fill(0, 12, 0);

        // Query to get the number of boxes by month for the given box_type and year.
        $this->db->select('MONTH(Crtdt) as month, COUNT(*) as count');
        $this->db->from('production_request');
        $this->db->where('YEAR(Crtdt)', $year);
        $this->db->group_by('MONTH(Crtdt)');
        $query = $this->db->get();

        // Fetch the results and map them to the corresponding month in the productionRequestData array.
        foreach ($query->result() as $row) {
            // Subtract 1 from the month because array indexes start at 0 (Jan = 0, Feb = 1, etc.).
            $productionRequestData[$row->month - 1] = (int) $row->count;
        }

        return $productionRequestData;
    }

    public function getQualityRequestData($year) {
        // Initialize an array with 12 entries (one for each month) and set the count to 0.
        $qualityRequestData = array_fill(0, 12, 0);

        // Query to get the number of boxes by month for the given box_type and year.
        $this->db->select('MONTH(Crtdt) as month, COUNT(*) as count');
        $this->db->from('quality_request');
        $this->db->where('YEAR(Crtdt)', $year);
        $this->db->group_by('MONTH(Crtdt)');
        $query = $this->db->get();

        // Fetch the results and map them to the corresponding month in the qualityRequestData array.
        foreach ($query->result() as $row) {
            // Subtract 1 from the month because array indexes start at 0 (Jan = 0, Feb = 1, etc.).
            $qualityRequestData[$row->month - 1] = (int) $row->count;
        }

        return $qualityRequestData;
    }

    public function getBox(){
        $this->db->select('b.*, s.SLoc');
        $this->db->from('box b');
        $this->db->join('storage s', 's.Id_storage = b.sloc', 'left');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getLogData($table){
        return $this->db->query("SELECT * FROM $table")->result_array();
    }
}