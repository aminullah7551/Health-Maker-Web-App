<?php $session = $this->request->session()->read("User");?>
<script>
$(document).ready(function(){		
	$(".mydataTable").DataTable({
		"responsive": true,
		"order": [[ 1, "asc" ]],
		"aoColumns":[
	                  {"bSortable": false},
	                  {"bSortable": true},
	                  {"bSortable": true},	                           
	                  {"bSortable": false}],
	"language" : {<?php echo $this->Health->data_table_lang();?>}	
	});
	var box_height = $(".box").height();
	var box_height = box_height + 300 ;
	$(".content-wrapper").css("height",box_height+"px");
	$(".content-wrapper").css("min-height","500px");
});		
</script>
<section class="content">
	<br>
	<div class="col-md-12 box box-default">		
		<div class="box-header">
			<section class="content-header">
			  <h1>
				<i class="fa fa-bars"></i>
				<?php echo __("Group List");?>
				<!-- <small><?php echo __("Group");?></small> -->
			  </h1>
			  <?php
				if($session["role_name"] == "administrator" || $session["role_name"] == "staff_member" || $session["role_name"] == "accountant")
				{ ?>
			  <ol class="breadcrumb">
				<a href="<?php echo $this->Health->createurl("HealthGroup","addGroup");?>" class="btn btn-flat btn-custom"><i class="fa fa-plus"></i> <?php echo __("Group");?></a>
			  </ol>
			  <?php } ?>
			</section>
		</div>
		<hr>
		<div class="box-body">
		<table class="mydataTable table table-striped">
			<thead>
				<tr>
					<th><?php echo __("Photo");?></th>
					<th><?php echo __("Group Name");?></th>
					<th><?php echo __("Total Group Members");?></th>					
					<th><?php echo __("Action");?></th>
				</tr>
			</thead>
			<tbody>
			<?php
			foreach($data as $row)
			{
				$image = ($row['image'] == "") ? "Thumbnail-img.png" : $row['image'];
				echo "
				<tr>					
					<td><img src='".$this->request->webroot ."upload/{$image}' class='membership-img img-circle'></img></td>
					<td>{$row['name']}</td>
					<td>".$this->Health->get_total_group_members($row["id"])."</td>
					<td>";			
				if($session["role_name"] == "administrator" || $session["role_name"] == "staff_member" ||$session["role_name"] == "accountant")
				{ 
					$confirmMsg = __("Are you sure,You want to delete this record?");
				echo "<a href='".$this->Health->createurl('HealthGroup','editGroup')."/{$row['id']}' class='btn btn-flat btn-primary' title='".__('Edit')."'><i class='fa fa-edit'></i></a>
					<a href='".$this->Health->createurl('HealthGroup','deleteGroup')."/{$row['id']}' class='btn btn-flat btn-danger' title='".__('Delete')."' onClick=\"return confirm('$confirmMsg')\"><i class='fa fa-trash-o'></i></a>";
				}
					echo " <a href='javascript:void(0)' data-url='".$this->request->base ."/HealthAjax/viewGroupMember/{$row['id']}' title='".__('View')."' class='view-grp-member btn btn-flat btn-info' id={$row['id']}><i class='fa fa-eye'></i></a>
					</td>
				</tr>
				";
			}
			?>
			</tbody>
			<tfoot>
				<tr>
					<th><?php echo __("Photo");?></th>
					<th><?php echo __("Group Name");?></th>
					<th><?php echo __("Total Group Members");?></th>					
					<th><?php echo __("Action");?></th>
				</tr>
			</tfoot>
		</table>
		</div>	
		<div class="overlay health-overlay">
		  <i class="fa fa-refresh fa-spin"></i>
		</div>
	</div>
</section>