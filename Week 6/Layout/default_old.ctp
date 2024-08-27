<!DOCTYPE html>
<html>
 <?= $this->Element('header') ?>
  <body class="hold-transition skin-green sidebar-mini">
    <div class="wrapper">
		 <?= $this->Element('topbar') ?>	
     
      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
			<?= ""/* $this->Element('sidebar')*/ ?>
		<?php 
		$session = $this->request->session()->read("User");
		$role_name = $session["role_name"];
		switch($role_name)
		{
			CASE "administrator":
				$menu_cell = $this->cell('HealthRenderMenu::adminMenu');
			break;
			
			CASE "member":
				$menu_cell = $this->cell('HealthRenderMenu::memberMenu');
			break;
			
			CASE "staff_member":
				$menu_cell = $this->cell('HealthRenderMenu::staffMenu');
			break;
			
			CASE "accountant":
				$menu_cell = $this->cell('HealthRenderMenu::accMenu');
			break;
		}	
		?>
		  
		<?= $menu_cell ?>
        </section>
        <!-- /.sidebar -->
      </aside>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
		<div class="body-overlay">
		  <i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>
		</div>
		 <script>
		   $(".body-overlay").css("display","block");
		   $("body").css("overflow-y","hidden");
		 </script>
		 <?= $this->Flash->Render() ?>
            <?= $this->fetch('content') ?>	
			<div class="modal fade health-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
			  <div class="modal-dialog modal-lg health-modal">
				<div class="modal-content">			
				
				</div>
			  </div>
			</div>
              
      </div><!-- /.content-wrapper -->
	  <?= $this->Element('footer') ?>
	  
	  
      <div class="control-sidebar-bg"></div>

     
      <!-- Add the sidebar's background. This div must be placed
           immediately after the control sidebar -->
      
    </div><!-- ./wrapper -->

    <!-- jQuery 2.1.4 -->

  </body>
</html>
