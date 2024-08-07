 <!-- ======= Header ======= -->
 <header id="header" class="header fixed-top d-flex align-items-center">

<div class="d-flex align-items-center justify-content-between">
  <a href="" class="logo d-flex align-items-center">
	<img src="<?=base_url('assets')?>/img/valeo.png" alt="">
  </a>
  <i class="bi bi-list toggle-sidebar-btn"></i>
</div><!-- End Logo -->


<nav class="header-nav ms-auto">
  <ul class="d-flex align-items-center">
	<li class="nav-item dropdown">

	  <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
		<i class="bi bi-bell"></i>
		<span class="badge bg-primary badge-number">
			<?php 
				$user = $this->db->get_where('user', ['name' => $this->session->userdata('name')])->row_array();
				$userid = $user['role_id'];
				$queryCount = $this->db->query("SELECT 
						COUNT(pr.id) AS notification_count
					FROM 
						production_request pr
					JOIN 
						user_access_submenu uas 
					ON 
						uas.submenu_id = 13 AND uas.role_id = '$userid'
					WHERE 
						pr.status = 'NEW'")->result_array();	 
				echo $queryCount[0]['notification_count'];
			?>
		</span>
	  </a><!-- End Notification Icon -->

	  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications" style="width: 350px">
	  	<li class="dropdown-header">
		  Material Request
		</li>
	  	<?php 
			$queryNotification = $this->db->query("SELECT 
					pr.id, 
					pr.Id_request, 
					pr.Production_plan, 
					pr.Id_material, 
					pr.Material_desc, 
					pr.Qty, 
					pr.Crtdt, 
					pr.Crtby, 
					ml.Uom
				FROM 
					production_request pr
				JOIN 
					user_access_submenu uas 
					ON uas.submenu_id = 13 AND uas.role_id = '$userid'
				JOIN 
					material_list ml 
					ON ml.Id_material = pr.Id_material
				WHERE 
					pr.status = 'NEW'
				ORDER BY 
					pr.Crtdt ASC
				LIMIT 5;
				")->result_array();
		?>

 		<?php foreach($queryNotification as $qn): ?>
		<li class="notification-item" style="padding: 8px">
		  <i class="bx bx-file text-warning"></i>
		  <div>
			<h6 style="font-size: 15px"><?=$qn['Id_request'];?></h6>
			<p><?=$qn['Material_desc'];?> | <?=$qn['Qty'];?> <?=$qn['Uom'];?></p>
			<div class="row">
				<p>
					<img src="<?=base_url('assets');?>/img/<?= $user['gender'] == 'Male' ? 'Man' : 'Woman';?>.png" alt="Profile" class="rounded-circle" width="20" height="20">
					<?=$qn['Crtby'];?>
				| <?=date_format(new DateTime($qn['Crtdt']), 'Y-m-d')?></p>
			</div>
		  </div>
		</li>

		<li>
		  <hr class="dropdown-divider">
		</li>
		<?php endforeach; ?>
	  </ul><!-- End Notification Dropdown Items -->
	</li><!-- End Notification Nav -->

	<li class="nav-item dropdown">

	  <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
		<i class="bi bi-calendar2"></i>
		<span class="badge bg-primary badge-number">
			<?php 
				$user = $this->db->get_where('user', ['name' => $this->session->userdata('name')])->row_array();
				$userid = $user['role_id'];
				$queryCount = $this->db->query("SELECT COUNT(rw.id_return) AS notification_rw_count
					FROM 
						return_warehouse rw
					JOIN 
						user_access_submenu uas 
						ON uas.submenu_id = 24 AND uas.role_id = '$userid'
					JOIN
						return_warehouse_detail rwd
						ON rwd.id_return = rw.id_return
					WHERE 
						rw.status = 1
					ORDER BY 
						rw.Crtdt DESC
					LIMIT 5")->result_array();	 
				echo $queryCount[0]['notification_rw_count'];
			?>
		</span>
	  </a><!-- End Notification Icon -->

	  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications" style="width: 350px">
	  	<li class="dropdown-header">
		  Return Request
		</li>
	  	<?php 
			$queryNotificationRW = $this->db->query("SELECT 
				rw.id_return,
				rw.box_type,
				rw.box_weight,
				rw.status,
				rwd.Id_material,
				rwd.Material_desc,
				rwd.Material_qty,
				rwd.Material_uom,
				rw.Crtdt,
				rw.Crtby
				FROM 
					return_warehouse rw
				JOIN 
					user_access_submenu uas 
					ON uas.submenu_id = 24 AND uas.role_id = '$userid'
				JOIN
					return_warehouse_detail rwd
					ON rwd.id_return = rw.id_return
				WHERE 
					rw.status = 1
				ORDER BY 
					rw.Crtdt DESC
				LIMIT 5")->result_array();
		?>

 		<?php foreach($queryNotificationRW as $rw): ?>
		<li class="notification-item" style="padding: 8px">
		  	<i class="bx bx-file text-warning"></i>
		  	<div>
				<h6 style="font-size: 15px"><?=$rw['id_return'];?> | <span style="font-size: small"><?=$rw['box_type'];?></span></h6>
				<p><?=$rw['Material_desc'];?> | <?=$rw['Material_qty'];?> <?=$rw['Material_uom'];?></p>
				<div class="row">
					<p>
						<img src="<?=base_url('assets');?>/img/<?= $user['gender'] == 'Male' ? 'Man' : 'Woman';?>.png" alt="Profile" class="rounded-circle" width="20" height="20">
						<?=$rw['Crtby'];?>
					| <?=date_format(new DateTime($rw['Crtdt']), 'Y-m-d')?></p>
				</div>
		  </div>
		</li>

		<li>
		  <hr class="dropdown-divider">
		</li>
		<?php endforeach; ?>
	  </ul><!-- End Notification Dropdown Items -->
	</li><!-- End Notification Nav -->

	<li class="nav-item dropdown">

	  <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
		<i class="bi bi-chat-left-text"></i>
		<span class="badge bg-success badge-number"></span>
	  </a><!-- End Messages Icon -->

	  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow messages">
		<!-- <li class="dropdown-header">
		  You have 3 new messages
		  <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
		</li>
		<li>
		  <hr class="dropdown-divider">
		</li>

		<li class="message-item">
		  <a href="#">
			<img src="<?=base_url('assets');?>/img/messages-1.jpg" alt="" class="rounded-circle">
			<div>
			  <h4>Maria Hudson</h4>
			  <p>Velit asperiores et ducimus soluta repudiandae labore officia est ut...</p>
			  <p>4 hrs. ago</p>
			</div>
		  </a>
		</li>
		<li>
		  <hr class="dropdown-divider">
		</li>

		<li class="message-item">
		  <a href="#">
			<img src="<?=base_url('assets');?>/img/messages-2.jpg" alt="" class="rounded-circle">
			<div>
			  <h4>Anna Nelson</h4>
			  <p>Velit asperiores et ducimus soluta repudiandae labore officia est ut...</p>
			  <p>6 hrs. ago</p>
			</div>
		  </a>
		</li>
		<li>
		  <hr class="dropdown-divider">
		</li>

		<li class="message-item">
		  <a href="#">
			<img src="<?=base_url('assets');?>/img/messages-3.jpg" alt="" class="rounded-circle">
			<div>
			  <h4>David Muldon</h4>
			  <p>Velit asperiores et ducimus soluta repudiandae labore officia est ut...</p>
			  <p>8 hrs. ago</p>
			</div>
		  </a>
		</li>
		<li>
		  <hr class="dropdown-divider">
		</li>

		<li class="dropdown-footer">
		  <a href="#">Show all messages</a>
		</li> -->

	  </ul><!-- End Messages Dropdown Items -->

	</li><!-- End Messages Nav -->

	<li class="nav-item dropdown pe-4">

	  <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
		<img src="<?=base_url('assets');?>/img/<?= $name['gender'] == 'Male' ? 'Man' : 'Woman';?>.png" alt="Profile" class="rounded-circle">
		<span class="d-none d-md-block dropdown-toggle ps-2"><?=$this->session->userdata('username');?></span>
	  </a><!-- End Profile Image Icon -->

	  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
		<li class="dropdown-header">
		<h6>
			<?= isset($name['name']) ? $name['name'] : $this->session->userdata('username'); ?>
		</h6>
		<span>
		<?php
			$role_query = $this->db->get('user_role');
			$role_mapping = [];

			foreach ($role_query->result_array() as $role) {
				$role_mapping[$role['id']] = $role['role'];
			}

			if (isset($name['name'])) {
				$role_id = $name['role_id'];
				echo isset($role_mapping[$role_id]) ? $role_mapping[$role_id] : 'Unknown Role';
			} else {
				echo 'Unknown';
			}
		?>
		</span>
		</li>

		<li>
		  <hr class="dropdown-divider">
		</li>

		<li>
		  <a class="dropdown-item d-flex align-items-center" href="<?=base_url('user');?>">
			<i class="bi bi-person"></i>
			<span>My Profile</span>
		  </a>
		</li>
		
		<li>
		  <hr class="dropdown-divider">
		</li>

		<li>
		  <a class="dropdown-item d-flex align-items-center" href="<?=base_url('user/change_password');?>">
			<i class="bx bx-lock"></i>
			<span>Change password</span>
		  </a>
		</li>

		<li>
		  <hr class="dropdown-divider">
		</li>
		
		<li>
		  <a class="dropdown-item d-flex align-items-center" href="<?=base_url('auth/logout');?>">
			<i class="bi bi-box-arrow-right"></i>
			<span>Sign Out</span>
		  </a>
		</li>

	  </ul><!-- End Profile Dropdown Items -->
	</li><!-- End Profile Nav -->

  </ul>
</nav><!-- End Icons Navigation -->

</header><!-- End Header -->