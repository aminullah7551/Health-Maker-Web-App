<script>
$(document).ready(function(){		
	$(".mydataTable").DataTable({
		"responsive": true,
		"order": [[ 1, "asc" ]],
		/*"aoColumns":[	                 
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},	                                            
	                  {"bSortable": false}],*/
	"language" : {<?php echo $this->Health->data_table_lang();?>}	
	});
});		
</script>
<section class="content">
	<br>
	<div class="col-md-12 box box-default">		
		<div class="box-header">
			<section class="content-header">
			  <h1>
				<i class="fa fa-bars"></i>
				<?php echo __("Sell Records");?>
				<!-- <small><?php echo __("Store");?></small> -->
			  </h1>
			  <?php 
			  if($role != 'member'){?>
			  <ol class="breadcrumb">				
				<a href="<?php echo $this->Health->createurl("HealthStore","sellProduct");?>" class="btn btn-flat btn-custom"><i class="fa fa-plus"></i> <?php echo __("Add Sell Product");?></a>
			  </ol>
			<?php } ?>
			</section>
		</div>
		<hr>
		<div class="box-body">
			<table class="mydataTable table table-striped">
			<thead>
				<tr>
					<th><?php echo __("Product Name");?></th>
					<th><?php echo __("Member Name");?></th>
					<th><?php echo __("Product Quantity");?></th>
					<th><?php echo __("Sell Date");?></th>
					<?php if($role != 'member'){ ?>
					<th><?php echo __("Action");?></th>
					<?php } ?>
				</tr>
			</thead>
			<tbody>
			<?php
			foreach($data as $row)
			{
				echo "<tr>";
				echo "<td>{$row['health_product']['product_name']}</td>
					  <td>{$row['health_member']['first_name']} {$row['health_member']['last_name']}</td>
					  <td>{$row['quantity']}</td>
					  <td>".$this->Health->get_db_format(date($this->Health->getSettings("date_format"),strtotime($row["sell_date"])))."</td>";
				//if($role == 'administrator' || $role == "staff_member")
				if($role != 'member')
				{	
					$confirmMsg = __("Are you sure you want to delete this product?");
					echo "<td>";
					echo "<a href='{$this->request->base}/HealthStore/editRecord/{$row["id"]}' class='btn btn-flat btn-primary' title='".__('Edit')."'><i class='fa fa-edit'></i></a>&nbsp;"	;
					echo "<a href='{$this->request->base}/HealthStore/deleteRecord/{$row['id']}' class='btn btn-flat btn-danger' title='".__('Delete')."' onclick=\"return confirm('$confirmMsg')\"><i class='fa fa-trash'></i></a>";
					echo "</td>";
				}
				
				echo "</tr>";
			}
			?>
			<tfoot>
				<tr>
					<th><?php echo __("Product Name");?></th>
					<th><?php echo __("Member Name");?></th>
					<th><?php echo __("Product Quantity");?></th>
					<th><?php echo __("Sell Date");?></th>
					<?php if($role != 'member'){ ?>
					<th><?php echo __("Action");?></th>
					<?php } ?>
				</tr>
			</tfoot>	
			</table>
		<!-- END -->
		</div>
		<div class='overlay health-overlay'>
			<i class='fa fa-refresh fa-spin'></i>
		</div>
	</div>
</section>
