<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">

      <!-- QUERY MENU -->
      <?php 
          $role_id = $this->session->userdata('role_id');
          $queryMenu = "SELECT `user_menu`.`id`, `menu`
                          FROM `user_menu` JOIN `user_access_menu`
                            ON `user_menu`.`id` = `user_access_menu`.`menu_id`
                        WHERE `user_access_menu`.`role_id` = $role_id
                      ORDER BY `user_access_menu`.`menu_id` ASC
                      ";
          $menu = $this->db->query($queryMenu)->result_array();
      ?>

      <!-- LOOPING MENU -->
      <?php foreach ($menu as $m) : ?>
        <hr>
        <li class="nav-heading"><?= $m['menu']; ?></li>

        <!-- SIAPKAN SUB-MENU SESUAI MENU -->
        <?php 
          $menuId = $m['id'];
          $querySubMenu = "SELECT *
                            FROM `user_sub_menu` JOIN `user_menu` 
                              ON `user_sub_menu`.`menu_id` = `user_menu`.`id`
                            WHERE `user_sub_menu`.`menu_id` = $menuId
                              AND `user_sub_menu`.`is_active` = 1
                      ";
          $subMenu = $this->db->query($querySubMenu)->result_array();
        ?>

        <?php foreach ($subMenu as $sm) : ?>   
        <li class="nav-item">
          <a class="nav-link collapsed" href="users-profile.html">
            <i class="<?= $sm['icon']; ?>"></i>
            <span>&nbsp;&nbsp;&nbsp;<?= $sm['title']; ?></span>
          </a>
        </li><!-- End Profile Page Nav -->
      <?php endforeach; ?>
    <?php endforeach; ?>
    </ul>

  </aside><!-- End Sidebar-->

  <main id="main" class="main">