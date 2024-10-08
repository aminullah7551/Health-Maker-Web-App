<script>
$(document).ready(function(){	

	var box_height = $(".box").height();
	var box_height = box_height + 200 ;
	$(".content-wrapper").css("height",box_height+"px");
});

</script>
<section class="content">
	<br>
	<div class="col-md-12 box box-default">		
		<div class="box-header">
			<section class="content-header">
			  <h1>
				<?php echo __("General Settings");?>
				<!-- <small><?php echo __("Settings");?></small> -->
			  </h1>			
			</section>
		</div>
		<hr>
		<div class="box-body">
		<?php
		
			echo $this->Form->create("settings",["type"=>"file","class"=>"validateForm form-horizontal","role"=>"form","id"=>"form"]);
			// echo $this->Form->create("settings",["type"=>"file","class"=>"validateForm form-horizontal","role"=>"form","id"=>"form"]);
			echo "<fieldset>";
			echo "<legend>".__('System Settings')."</legend>";
			echo "<div class='form-group'>";
			echo "<label class='control-label col-md-2'>".__("Name")."<span class='text-danger'> *</span></label>";
			echo "<div class='col-md-8'>";			
			echo $this->Form->input("",["name"=>"name","class"=>"form-control validate[required]","label"=>false,"value"=> (($edit) ? $data['name'] : "")]);
			echo "</div>";
			echo "</div>";
			
			echo "<div class='form-group'>";
			echo "<label class='control-label col-md-2'>".__("Starting Year")."<span class='text-danger'> *</span></label>";
			echo "<div class='col-md-8'>";	
			echo $this->Form->input("",["label"=>false,"name"=>"start_year","class"=>"form-control validate[required,custom[onlyNumberSp]]","value"=> (($edit) ? $data['start_year'] : "")]);
			echo "</div>";					
			echo "</div>";					
			
		
			
			echo "<div class='form-group'>";
			echo "<label class='control-label col-md-2'>". __("System Language")."</label>";
			echo "<div class='col-md-8'>";
			$sys_language = $this->Health->getSettings("sys_language");
			
			?>
				<?php echo "<input type='hidden' id='s_lang' value='{$sys_language}'>";?>
				
				<select id="sys_language" class="form-control" name="sys_language" id="s_lang">
					<option value="en">English/en</option>
					<option value="hi">Hindi/hi</option>
					<option value="ar">Arabic/ar</option>
					<option value="zh_CN">Chinese/zh-CN</option>
					<option value="cs">Czech/cs</option>
					<option value="fr">French/fr</option>
					<option value="de">German/de</option>
					<option value="el">Greek/el</option>					
					<option value="it">Italian/it</option>	
					<option value="ja">Japan/ja</option>
					<option value="ko">Korean/ko</option>
					<option value="pl">Polish/pl</option>
					<option value="pt_BR">Portuguese-BR/pt-BR</option>
					<option value="pt_PT">Portuguese-PT/pt-PT</option>						
					<option value="fa">Persian/fa</option>
					<option value="ru">Russian/ru</option>
					<option value="es">Spanish/es</option>											
					<option value="th">Thai/th</option>
					<option value="tr">Turkish/tr</option>
					<option value="ca">Catalan/ca</option>
					<option value="da">Danish/da</option>
					<option value="et">Estonian/et</option>
					<option value="fi">Finnish/fi</option>
					<option value="he">Hebrew (Israel)/he</option>
					<option value="hr">Croatian/hr</option>
					<option value="hu">Hungarian/hu</option>
					<option value="id">Indonesian/id</option>
					<option value="lt">Lithuanian/lt</option>
					<option value="nl">Dutch/nl</option>
					<option value="no">Norwegian/no</option>
					<option value="ro">Romanian/ro</option>
					<option value="sv">Swadish/sv</option>
					<option value="vi">Vietnamese/vi</option>
					<option value="gu">Gujarati/gu</option>
					<option value="ta">Tamil/ta</option>
					<option value="te">Telugu/te</option>
					<option value="mr">Marthi/mr</option>
					<option value="kn">Kannada/kn</option>
					<option value="bn">Bangali/bn</option>
					<option value="ml">Malyalam/ml</option>
					<option value="ur">Urdu/ur</option>
					<option value="or">Odia/or</option>
					<option value="bg">Bulgaria/bg</option>
					<option value="is">Iceland/is</option>
					<option value="lb">Luxembourg/lb</option>
					<option value="lv">Latvian/lv</option>
					<option value="sk">Slovak/sk</option>
					<option value="sl">Slovenia/sl</option>
					<option value="zu">Zulu/zu</option>
				</select>
				
				<script>
					var sys_lang = $("#s_lang").val();
					$("#sys_language option[value="+sys_lang+"]").prop("selected",true);
				</script>
			<?php
			echo "</div>";
			echo "</div>";
			
			echo "<div class='form-group'>";
			echo "<label class='control-label col-md-2'>".__("Set Language to RTL")."</label>";
			echo "<div class='col-md-8 checkbox'><label class='save_enable'>";
			echo $this->Form->checkbox("enable_rtl",["value"=>"1","checked"=>(($edit && $data['enable_rtl'] == true)? true : false)])." ".__("Enable");
			echo "</label></div>";
			echo "</div>";	
			
						echo "<div class='form-group'>";
			echo "<label class='control-label col-md-2'>".__("Email")."<span class='text-danger'>*</span></label>";
			echo "<div class='col-md-8'>";
			echo $this->Form->input("",["label"=>false,"name"=>"email","class"=>"form-control validate[required,custom[email]]","label"=>false,"value"=> (($edit) ? $data['email'] : "")]);
			echo "</div>";	
			echo "</div>";	
			
			echo "<div class='form-group'>";
			echo "<label class='control-label col-md-2'>".__("Date Format")."</label>";
			echo "<div class='col-md-8'>";				
			
			// $format = ["F j, Y"=>date("F j, Y"),"Y-m-d"=>date("Y-m-d"),"m/d/Y"=>date("m/d/Y")];
			$format = ["Y-m-d"=>date("Y-m-d"),"m/d/Y"=>date("m/d/Y")];
			
			$default = ($edit && !empty($data['date_format'])) ? [$data['date_format']] : ['yy/mm/dd'];
			echo $this->Form->select("date_format",$format,["default"=>$default,"class"=>"form-control plan_list validate[required]"]);
			echo "</div>";	
			echo "</div>";
			
			
			#########################################################################
			echo "<div class='form-group'>";			
			echo "<label class='control-label col-md-2'>".__("Time Zone")."</label>";
			echo "<div class='col-md-8'>";	
			$time_zone = ($edit && !empty($data['time_zone'])) ? $data['time_zone'] : date_default_timezone_get();
			?>
				<input type="hidden" value="<?php echo $time_zone;?>" id="timezone_val">
				<select id="timezone-selector" name="time_zone" class="form-control">
					<?php
						
						foreach(timezone_abbreviations_list() as $abbr => $timezone){
								foreach($timezone as $val)
								{
										if(isset($val['timezone_id']))
										{
												
											$selected = ($val['timezone_id'] == $data['time_zone'])?'selected':'';
											echo '<option value="'.$val['timezone_id'].'" '.$selected.'>'.$val['timezone_id'].'</option>';
										}
								}
						}
					?>
				</select>
			<?php
			echo "</div>";
			echo "</div>";	
			
			echo "<div class='form-group'>";			
			echo "<label class='control-label col-md-2'>".__("Logo")."</label>";
			echo "<div class='col-md-6'>";			
			echo $this->Form->file("",["name"=>"health_logo","class"=>"form-control"]);
			echo "</div>";
			echo "<div class='col-md-2'>";	
			echo __("(Max. height 50px.)");
			echo "</div>";		
			
			echo "</div>";		
			
			$src = ($edit && !empty($data['health_logo'])) ? $data['health_logo'] : "logo.png" ; 
			echo "<div class='col-md-offset-2'>";
			echo $this->Form->input("",["type" => "hidden","name"=>"old_health_logo","class"=>"form-control","value"=>$src]);
			echo "<img src='{$this->request->webroot}webroot/upload/{$src}'><br><br><br>";
			echo "</div>";
			
			echo "<div class='form-group'>";
			echo "<label class='control-label col-md-2'>".__("Cover Image")."</label>";
			echo "<div class='col-md-8'>";
			echo $this->Form->file("",["name"=>"cover_image","class"=>"form-control"]);
			echo "</div>";
			echo "</div>";
			
			$src = ($edit && !empty($data['cover_image'])) ? $data['cover_image'] : "cover-image.png" ;
			echo $this->Form->input("",["type" => "hidden","name"=>"old_cover_image","class"=>"form-control","value"=>$src]);
			echo "<img src='{$this->request->webroot}webroot/upload/{$src}' style='max-width: 100%;'>";			
			echo "</fieldset><br><br>";
			
			echo "<fieldset>";
			echo "<legend>".__('Measurement Units')."</legend>";
			echo "<div class='form-group'>";
			echo "<label class='control-label col-md-2'>".__("Weight")."<span class='text-danger'> *</span></label>";
			echo "<div class='col-md-8'>";			
			echo $this->Form->input("",["label"=>false,"name"=>"weight","class"=>"form-control validate[required,custom[onlyLetterNumber]]","label"=>false,"value"=> (($edit) ? $data['weight'] : "")]);
			echo "</div>";
			echo "</div>";
			
			echo "<div class='form-group'>";
			echo "<label class='control-label col-md-2'>".__("Height")."<span class='text-danger'> *</span></label>";
			echo "<div class='col-md-8'>";			
			echo $this->Form->input("",["label"=>false,"name"=>"height","class"=>"form-control validate[required,custom[onlyLetterNumber]]","label"=>false,"value"=> (($edit) ? $data['height'] : "")]);
			echo "</div>";
			echo "</div>";
			
			echo "<div class='form-group'>";
			echo "<label class='control-label col-md-2'>".__("Chest")."<span class='text-danger'> *</span></label>";
			echo "<div class='col-md-8'>";				
			echo $this->Form->input("",["label"=>false,"name"=>"chest","class"=>"form-control validate[required,custom[onlyLetterNumber]]","label"=>false,"value"=> (($edit) ? $data['chest'] : "")]);
			echo "</div>";
			echo "</div>";
			
			echo "<div class='form-group'>";
			echo "<label class='control-label col-md-2'>".__("Waist")."<span class='text-danger'> *</span></label>";
			echo "<div class='col-md-8'>";
			echo $this->Form->input("",["label"=>false,"name"=>"waist","class"=>"form-control validate[required,custom[onlyLetterNumber]]","label"=>false,"value"=> (($edit) ? $data['waist'] : "")]);
			echo "</div>";
			echo "</div>";
			
			echo "<div class='form-group'>";
			echo "<label class='control-label col-md-2'>".__("Thing")."<span class='text-danger'> *</span></label>";
			echo "<div class='col-md-8'>";
			echo $this->Form->input("",["label"=>false,"name"=>"thing","class"=>"form-control validate[required,custom[onlyLetterNumber]]","label"=>false,"value"=> (($edit) ? $data['thing'] : "")]);
			echo "</div>";
			echo "</div>";
			
			echo "<div class='form-group'>";
			echo "<label class='control-label col-md-2'>".__("Arms")."<span class='text-danger'> *</span></label>";
			echo "<div class='col-md-8'>";
			echo $this->Form->input("",["label"=>false,"name"=>"arms","class"=>"form-control validate[required,custom[onlyLetterNumber]]","label"=>false,"value"=> (($edit) ? $data['arms'] : "")]);
			echo "</div>";
			echo "</div>";
			
			echo "<div class='form-group'>";
			echo "<label class='control-label col-md-2'>".__("Fat")."<span class='text-danger'> *</span></label>";
			echo "<div class='col-md-8'>";			
			echo $this->Form->input("",["label"=>false,"name"=>"fat","class"=>"form-control validate[required,custom[onlyLetterNumber]]","label"=>false,"value"=> (($edit) ? $data['fat'] : "")]);
			echo "</div>";
			echo "</div>";
			echo "</fieldset><br><br>";
			
			
			echo "<fieldset>";
			echo "<legend>".__('Message Setting')."</legend>";
			echo "<div class='form-group'>";
			echo "<label class='control-label col-md-2'>".__("Member can Message To other's")."</label>";
			echo "<div class='col-md-8 checkbox'><label class='radio-inline'>";			
			echo $this->Form->checkbox("enable_message",["value"=>"1","checked"=>(($edit && $data['enable_message'] == true)? true : false)])." ".__("Enable");
			echo "</label></div></div>";
			echo "</fieldset><br><br>";	

			echo "<fieldset>";
			echo "<legend>".__('Theme Setting')."</legend>";
			echo "<div class='form-group'>";
			echo "<label class='control-label col-md-2'>".__("Side Menu Color")."</label>";
			echo "<div class='col-md-8'><label class='radio-inline'>";	
			echo "<input type='color' name='sidemenu_color' value='".$data['sidemenu_color']."'>";
			echo "</label></div></div>";
			echo "<div class='form-group'>";
			echo "<label class='control-label col-md-2'>".__("Header Color")."</label>";
			echo "<div class='col-md-8'><label class='radio-inline'>";			
			echo "<input type='color' name='header_color' value='".$data['header_color']."'>";
			echo "</label></div></div>";
			echo "</fieldset><br><br>";
			
			echo "<fieldset>";
			echo "<legend>".__('Header & Footer Text')."</legend>";
			echo "<div class='form-group'>";			
			echo "<label class='control-label col-md-2'>".__("Left Header Text")."</label>";
			echo "<div class='col-md-8'>";
			echo $this->Form->input("",["label"=>false,"name"=>"left_header","class"=>"form-control","value"=>(($edit) ? $data['left_header'] : "")]);
			echo "</div>";
			echo "</div>";	
			
			echo "<div class='form-group'>";			
			echo "<label class='control-label col-md-2'>".__("Footer Text")."</label>";
			echo "<div class='col-md-8'>";
			echo $this->Form->input("",["label"=>false,"name"=>"footer","class"=>"form-control","value"=>(($edit) ? $data['footer'] : "")]);
			echo "</div>";
			echo "</div>";				
			echo "</fieldset><br><br>";	
			
			echo "<div class='col-md-offset-2'>";
			echo $this->Form->button(__("Save Settings"),['class'=>"btn btn-flat btn-success submit_button","name"=>"save_setting"]);
			echo "</div>";
			echo $this->Form->end();
			
		?>	
		</div>	
		<div class="overlay health-overlay">
		  <i class="fa fa-refresh fa-spin"></i>
		</div>
	</div>
</section>
<script>
	// Disable button after click 
	$(document).on('submit','#form',function(){	
		$(".submit_button").attr('disabled', 'disabled');			
	});
</script>
