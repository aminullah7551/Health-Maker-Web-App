<?php $session = $this->request->session(); ?>

		  <div class="user-panel">
            <div class="pull-left image">
			  <?php 
			  $user_img = $session->read("User.profile_img");
			  echo $this->Html->image("../webroot/upload/{$user_img}",array("class"=>"img-circle","alt"=>"User Image")); ?>
			</div>
            <div class="pull-left info">
              <p><?php echo $session->read("User.display_name");?></p>
              <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
          </div>
		  <ul class="sidebar-menu">	 
            <li class= "treeview <?php echo ($this->request->controller == "Dashboard") ? "active" : "";?>">
              <a href="<?php echo $this->Health->createurl("Dashboard","index");?>">
                <i class="fa fa-pie-chart"></i> <span><?php echo __('Dashboard');?></span></i> 
              </a>             
            </li>		
			<li class="treeview <?php echo ($this->request->controller == "HealthGroup") ? "active" : "";?>">
              <a href="<?php echo $this->Health->createurl("HealthGroup","GroupList");?>">
                <i class="fa fa-object-group"></i> <span><?php echo __('Group');?></span> 
              </a>
			</li>	
			<li class="treeview <?php echo ($this->request->controller == "HealthNutrition") ? "active" : "";?>">
              <a href="<?php echo $this->Health->createurl("HealthNutrition","nutritionList");?>">
                <i class="fa fa-calendar"></i> <span><?php echo __("Nutrition Schedule");?></span><i class="fa fa-angle-left <?php echo $pull;?>"></i>
              </a>
			  <ul class="treeview-menu">					
					<li class="<?php echo ($this->request->action == "nutritionList" || $this->request->action == "addnutritionSchedule" || $this->request->action == "viewNutirion") ? "active" : "";?>">
						<a href="<?php echo $this->Health->createurl("HealthNutrition","nutritionList");?>"><i class="fa fa-circle-o"></i><?php echo __('Nutrition Schedule');?></a>
					</li>	
              </ul>	
			</li>
			<li class="treeview <?php echo ($this->request->controller == "HealthMember" ) ? "active" : "";?>">
              <a href="<?php echo $this->Health->createurl("HealthMember","memberList");?>">
                <i class="fa fa-user"></i> <span><?php echo __('Member Management');?></span></i><i class="fa fa-angle-left <?php echo $pull;?>"></i>
              </a>
			   <ul class="treeview-menu">
					<li class="<?php echo ($this->request->action == "memberList" || $this->request->action == "addMember" || $this->request->action == "editMember" || $this->request->action == "viewMember") ? "active" : "";?>">
						<a href="<?php echo $this->Health->createurl("HealthMember","memberList");?>"><i class="fa fa-circle-o"></i><?php echo __('Members');?></a>
					</li>
              </ul>			  
			</li>
			<li class="treeview <?php echo ($this->request->controller == "Activity") ? "active" : "";?>">
              <a href="<?php echo $this->Health->createurl("Activity","activityList");?>">
                <i class="fa fa-bicycle"></i> <span><?php echo __('Activity');?></span>  
              </a>
			</li>
			<li class="treeview <?php echo ($this->request->controller == "HealthAssignWorkout" || $this->request->controller == "HealthDailyWorkout") ? "active" : "";?>">
				<a href="<?php echo $this->Health->createurl("HealthAssignWorkout","WorkoutLog");?>">
					<i class="fa fa-hand-grab-o"></i> <span><?php echo __('Workout');?></span><i class="fa fa-angle-left <?php echo $pull;?>"></i>
				</a>
			   <ul class="treeview-menu">
					<li class="<?php echo ($this->request->action == "workoutLog" || $this->request->action == "assignWorkout" || $this->request->action == "viewWorkouts") ? "active" : "";?>">
						<a href="<?php echo $this->Health->createurl("HealthAssignWorkout","WorkoutLog");?>"><i class="fa fa-circle-o"></i><?php echo __('Assign Workout');?></a>
					</li>
					<li class="<?php echo ($this->request->action == "workoutList" || $this->request->action == "addWorkout" || $this->request->action =="addMeasurment" || $this->request->action =="viewWorkout" || $this->request->action =="editMeasurment") ? "active" : "";?>">
						<a href="<?php echo $this->Health->createurl("HealthDailyWorkout","workoutList");?>">
							<i class="fa fa-circle-o"></i><span><?php echo __('Daily Workout');?></span></i>
						</a>
					</li>				
              </ul>	
			</li>			
		
			<li class="treeview <?php echo ($this->request->controller == "HealthReservation") ? "active" : "";?>">
              <a href="<?php echo $this->Health->createurl("HealthReservation","reservationList");?>">
                <i class="fa fa-ticket"></i> <span><?php echo __("Event");?></span>  
              </a>
			</li>
			<li class="treeview <?php echo ($this->request->controller == "HealthAttendance") ? "active" : "";?>">
              <a href="<?php echo $this->Health->createurl("HealthAttendance","attendance");?>">
                <i class="fa fa-braille"></i> <span><?php echo __("Daily Fitness Record");?></span>  
              </a>
			</li>			
			
			<li class="treeview <?php echo ($this->request->controller == "HealthMessage") ? "active" : "";?>">
              <a href="<?php echo $this->Health->createurl("HealthMessage","composeMessage");?>">
                <i class="fa fa-commenting"></i> <span><?php echo __("Message");?></span>  
              </a>
			</li>
			<?php if($role_name == 'administrator')
			{?>
			<li class="treeview <?php echo ($this->request->controller == "GeneralSetting") ? "active" : "";?>">
              <a href="<?php echo $this->Health->createurl("GeneralSetting","SaveSetting");?>">
                <i class="fa fa-sliders"></i> <span><?php echo __("General Settings");?></span></i>
              </a>
			</li>
			<li class="treeview <?php echo ($this->request->controller == "HealthAccessright") ? "active" : "";?>">
              <a href="<?php echo $this->Health->createurl("HealthAccessright","accessRight");?>">
                <i class="fa fa-key"></i> <span><?php echo __("Access Right");?></span></i>
              </a>
			</li>
			<?php } ?>
          </ul>
      