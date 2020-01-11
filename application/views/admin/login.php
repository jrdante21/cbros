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
	<div class="uk-box-shadow-bottom uk-position-large uk-position-top-center">
		<div class="uk-background-default uk-padding-small uk-text-center">
			<div>
				<img src="<?= base_url()?>images/cbros-logo.png" width="150">
			</div>
			<h4 class="uk-margin-small uk-heading-line"><span>CBROS Admin</span></h4>
			<div>
				<form method="post" action="<?= site_url('CBROS/login')?>">
					<div class="uk-margin">
				        <div class="uk-inline">
				            <span class="uk-form-icon" uk-icon="icon: user"></span>
				            <input class="uk-input" type="text" name="username" placeholder="Username" autocomplete="off">
				        </div>
				    </div>

				    <div class="uk-margin">
				        <div class="uk-inline">
				            <span class="uk-form-icon" uk-icon="icon: lock"></span>
				            <input class="uk-input" type="password" name="password" placeholder="Password">
				        </div>
				    </div>

				    <button class="uk-button uk-button-primary uk-width-1-1">Log in</button>
				</form>
				<?php if ($err !== null): ?>
					<?php if ($err === 'err1'): ?>
						<div class="uk-alert-danger uk-margin-remove-bottom" uk-alert>
						    <a class="uk-alert-close" uk-close></a>
						    <p>Username not found!</p>
						</div>
					<?php elseif ($err === 'err2'): ?>
						<div class="uk-alert-danger uk-margin-remove-bottom" uk-alert>
						    <a class="uk-alert-close" uk-close></a>
						    <p>Incorrect password!</p>
						</div>
					<?php elseif ($err === 'err3'): ?>
						<div class="uk-alert-danger uk-margin-remove-bottom" uk-alert>
						    <a class="uk-alert-close" uk-close></a>
						    <p>Account has been deactivated!</p>
						</div>
					<?php endif ?>
				<?php endif ?>
			</div>
		</div>
	</div>
</body>
</html>