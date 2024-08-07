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
}