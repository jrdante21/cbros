<?php
	$navig = [
		'Home' 			=> ['cls' => '', 'url' => 'home', 'icon' => 'home'],
		'Employees' 	=> ['cls' => '', 'url' => 'employees', 'icon' => 'users'],
		'Groups' 		=> ['cls' => '', 'url' => 'groups', 'icon' => 'album'],
		'Recycle Bin' 	=> ['cls' => '', 'url' => 'recycle_bin', 'icon' => 'trash']
	];
	if ($nav != 'Profile') {
		$navig[$nav]['cls'] = 'active';
	}
?>
<!DOCTYPE html>
<html class="uk-background-muted">
<head>
	<title>C-BROS</title>
	<link rel="icon" type="image/jpg" href="<?=base_url();?>images/cbros-logo.png">
	<link rel="stylesheet" type="text/css" href="<?=base_url();?>css/uikit.min.css">
	<link rel="stylesheet" type="text/css" href="<?=base_url();?>css/custom2.css">
	<link rel="stylesheet" type="text/css" href="<?= base_url()?>foundation/css/foundation-datepicker.min.css">

	<script type="text/javascript" src="<?=base_url();?>js/uikit.min.js"></script>
	<script type="text/javascript" src="<?=base_url();?>js/uikit-icons.min.js"></script>
	<script type="text/javascript" src="<?=base_url();?>js/jquery-3.3.1.js"></script>
	<script type="text/javascript" src="<?= base_url()?>foundation/js/foundation-datepicker.min.js"></script>
</head>
<body>
	<div class="dashboard-nav uk-box-shadow-small bg-gradient uk-light collapse">
		<div class="uk-padding-small uk-text-center">
			<div>
				<img src="<?= base_url()?>images/cbros-logo.png" width="70"> 
			</div>
			<div style="color:#fff; font-size: 12px;">
				C-BROS CONSTRUCTION AND SAM'S MOUNTAIN VIEW RIDGE RESORT AND HOTEL
			</div>
		</div>
		<hr class="uk-margin-remove-top">
		<ul class="uk-nav-default uk-nav-parent-icon" uk-nav>
	        <li class="uk-parent">
	            <a href="#"><span uk-icon="icon:plus; ratio: 1.3" class="uk-margin-small-right"></span> Add New</a>
	            <ul class="uk-nav-sub">
	                <li><a href="#" uk-toggle="#modalAddEmployee">Add Employee</a></li>
	                <li><a href="#" uk-toggle="#modalAddGroup">Add Group</a></li>
	            </ul>
	        </li>
			<?php foreach ($navig as $nk => $nv): ?>
	        <li class="<?=$nv['cls']?>">
	        	<a href="<?= site_url('CBROS/'.$nv['url'])?>"><span uk-icon="icon:<?=$nv['icon']?>; ratio: 1.3" class="uk-margin-small-right"></span> <?=$nk?></a>
	        </li>
			<?php endforeach ?>
			<hr>
	        <li class="<?php if($nav == 'Profile'){ echo 'active'; }?>">
	        	<a href="<?= site_url('CBROS/profile')?>"><span uk-icon="icon:user; ratio: 1.3" class="uk-margin-small-right"></span> Profile</a>
	        </li>
	        <li>
	        	<a href="<?= site_url('CBROS/logout')?>"><span uk-icon="icon:sign-out; ratio: 1.3" class="uk-margin-small-right"></span> Log Out</a>
	        </li>
		</ul>
	</div>
	<div class="dashboard-main">
		<div>
			<?=$content?>
		</div>
	</div>

<div id="modalAddEmployee" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
    	<form action="<?= site_url('CBROS/add_new/employee')?>" method="POST">
	        <h2 class="uk-modal-title uk-margin-remove">Add Employee</h2>
	        <h3 class="uk-margin-small">General Information</h3>
	        <div class="uk-grid-small uk-child-width-1-3" uk-grid>
	        	<div>
	        		<label>First Name</label>
	        		<input type="text" name="Addfname" class="uk-width-1-1 uk-input uk-form-small" required>
	        	</div>
	        	<div>
	        		<label>Middle Name</label>
	        		<input type="text" name="Addmname" class="uk-width-1-1 uk-input uk-form-small" required>
	        	</div>
	        	<div>
	        		<label>Last Name</label>
	        		<input type="text" name="Addlname" class="uk-width-1-1 uk-input uk-form-small" required>
	        	</div>
	        </div>
	        <div class="uk-margin-small">
	        	<label>Gender</label>
	        	<div>
	        		<span class="uk-margin-small-right"><input type="radio" name="Addgender" value="1" class="uk-radio" required> Male</span>
	        		<span><input type="radio" name="Addgender" value="2" class="uk-radio" required> Female</span>
	        	</div>
	        </div>

	        <h3 class="uk-margin-remove">Work Details</h3>
	        <div class="uk-grid-small uk-child-width-1-2" uk-grid>
		        <div>
		    		<label>Position</label>
		    		<input type="text" name="Addpos" class="uk-width-1-1 uk-input uk-form-small" required>
		        </div>
		        <div>
		    		<label>Designation</label>
		    		<input type="text" name="Adddesig" class="uk-width-1-1 uk-input uk-form-small" required>
		        </div>
	        </div>
	        <div class="uk-margin-small">
	    		<label>Group</label>
	    		<select class="uk-select uk-form-small" name="Addgroup">
	    			<?php foreach ($group as $g): ?>
	    				<option value="<?=$g['gID']?>"><?=$g['gName']?></option>
	    			<?php endforeach ?>
	    		</select>
	        </div>
	        <p class="uk-text-right">
	            <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
	            <button class="uk-button uk-button-primary">Continue</button>
	        </p>
    	</form>
    </div>
</div>

<div id="modalAddGroup" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
    	<form action="<?= site_url('CBROS/add_new/group')?>" method="POST">
	        <h2 class="uk-modal-title">Add Group</h2>
	        <p>
	    		<label>Group Name</label>
	    		<input type="text" name="AddGroupname" class="uk-width-1-1 uk-input uk-form-small">
	        </p>
	        <div class="uk-grid-small uk-child-width-1-2" uk-grid>
	        	<div>
	        		<label>Date Start</label>
	        		<input type="text" autocomplete="off" name="AddGroupdateS" class="uk-width-1-1 uk-input uk-form-small">
	        	</div>
	        	<div>
	        		<label>Date End</label>
	        		<input type="text" autocomplete="off" name="AddGroupdateE" class="uk-width-1-1 uk-input uk-form-small">
	        	</div>
	        </div>
	        <p class="uk-text-right">
	            <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
	            <button class="uk-button uk-button-primary">Save</button>
	        </p>
	    </form>
    </div>
</div>
<script type="text/javascript">
	
	$(document).ready(function(){
		$("[name=AddGroupdateS]").fdatepicker({format:'yyyy-mm-dd'});
		$("[name=AddGroupdateE]").fdatepicker({format:'yyyy-mm-dd'});
	});
</script>
</body>
</html>