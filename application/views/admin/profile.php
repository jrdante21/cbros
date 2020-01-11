<div class="uk-grid-small uk-child-width-1-3@m" uk-grid>
	<div>
		<div class="uk-padding-small uk-box-shadow-small uk-background-default">
			<h3><i uk-icon="icon:user;ratio:2;"></i> Edit Username</h3>
		    <div class="uk-margin">
		        <label class="uk-form-label">Username</label>
		        <div class="uk-form-controls">
		            <input class="uk-input uk-form-small" value="<?=$admin?>" name="nuser" type="text" required>
		        </div>
		    </div>
		    <div class="uk-margin">
		        <label class="uk-form-label">Confirm Password</label>
		        <div class="uk-form-controls">
		            <input class="uk-input uk-form-small" name="pword" type="password" required>
		        </div>
		    </div>
		    <div>
		    	<button class="uk-button uk-button-primary uk-button-small uk-width-1-1" id="btnUsername">Confirm</button>
		    </div>
		</div>
	</div>
	<div>
		<div class="uk-padding-small uk-box-shadow-small uk-background-default">
			<h3><i uk-icon="icon:lock;ratio:2;"></i> Edit Password</h3>
			<small>Please use a strong password that you're not using elsewhere.</small>
		    <div class="uk-margin">
		        <label class="uk-form-label">New Password</label>
		        <div class="uk-form-controls">
		            <input class="uk-input uk-form-small" name="npwrd" type="password" required>
		        </div>
		    </div>
		    <div class="uk-margin">
		        <label class="uk-form-label">Confirm New Password</label>
		        <div class="uk-form-controls">
		            <input class="uk-input uk-form-small" name="cpwrd" type="password" required>
		        </div>
		    </div>
		    <div class="uk-margin">
		        <label class="uk-form-label">Confirm Old Password</label>
		        <div class="uk-form-controls">
		            <input class="uk-input uk-form-small" name="opwrd" type="password" required>
		        </div>
		    </div>
		    <div>
		    	<button class="uk-button uk-button-primary uk-button-small uk-width-1-1" id="btnPassword">Confirm</button>
		    </div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$("#btnUsername").click(function(){
			$("#btnUsername").attr('disabled','disabled').attr('uk-spinner','ratio:1');
			var data = {};
			data.pwrd = $("[name=pword]").val();
			data.user = $("[name=nuser]").val();
			$.post({
				url: "<?=site_url('CBROS/profile_ajax_requests/username/')?>",
				data: data,
				success: function (res) {
					$("#btnUsername").removeAttr('disabled').removeAttr('uk-spinner');
					var r = JSON.parse(res);
					UIkit.notification({  message: r.msg, status: r.stat });
				}
			})
		});


		$("#btnPassword").click(function(){
			$("#btnPassword").attr('disabled','disabled').attr('uk-spinner','ratio:1');
			var data = {};
			data.pwrd = $("[name=opwrd]").val();
			data.npwrd = $("[name=npwrd]").val();
			data.cpwrd = $("[name=cpwrd]").val();
			$.post({
				url: "<?=site_url('CBROS/profile_ajax_requests/password/')?>",
				data: data,
				success: function (res) {
					$("#btnPassword").removeAttr('disabled').removeAttr('uk-spinner');
					var r = JSON.parse(res);
					UIkit.notification({  message: r.msg, status: r.stat });
				}
			})
		});
	});
</script>
