<div class="uk-flex uk-flex-between <?=$stat['cls']?>">
	<div class="uk-button-group uk-background-default">
		<?php foreach ($nav as $nk => $nv): ?>
			<a href="<?=$nv['url']?>" class="uk-button <?=$nv['cls']?>"><?=$nk?></a>
		<?php endforeach ?>
	</div>
	<div>
		<?=$stat['btn']?>
	</div>
</div>
<?php
	$sex = [1 => 'Male', 2 => 'Female'];
?>
<div class="uk-padding-small uk-box-shadow-small uk-background-default">
	<div class="uk-grid-small uk-child-width-1-2@m" uk-grid>
		<div class="uk-width-2-3@m">
			<h3 class="uk-heading-line">
				<span>General Information 
				[<button class="uk-button uk-button-text" uk-toggle="#modalGeneral">Edit</button>]</span>
			</h3>
			<div class="uk-grid-small uk-child-width-1-3@m" uk-grid>
				<div>
					<div class="uk-text-lead"><?=$emp->emply_id?></div>
					<div class="uk-text-meta">Employee's ID</div>
				</div>
				<div class="uk-width-2-3@m">
					<div class="uk-text-lead" id="Ename"><?=$emp->emply_fname.' '.$emp->emply_mname.' '.$emp->emply_lname?></div>
					<div class="uk-text-meta">Employee's Name</div>
				</div>
				<div>
					<div class="uk-text-lead" id="Esex"><?=$sex[$emp->sex]?></div>
					<div class="uk-text-meta">Gender</div>
				</div>
				<div>
					<div class="uk-text-lead" id="Ebday"><?=$emp->nbday?></div>
					<div class="uk-text-meta">Birthdate</div>
				</div>
				<div>
					<div class="uk-text-lead" id="Eage"><?=$emp->age?></div>
					<div class="uk-text-meta">Age</div>
				</div>
				<div>
					<div class="uk-text-lead" id="Econtact"><?=$emp->contact?></div>
					<div class="uk-text-meta">Contact Number</div>
				</div>
				<div class="uk-width-2-3@m">
					<div class="uk-text-lead" id="Eaddress"><?=$emp->emply_address?></div>
					<div class="uk-text-meta">Address</div>
				</div>
			</div>

			<h3 class="uk-heading-line uk-margin">
				<span>Government ID's 
				[<button class="uk-button uk-button-text" uk-toggle="#modalGov">Edit</button>]</span>
			</h3>
			<div class="uk-grid-small uk-child-width-1-3@m" uk-grid>
				<div>
					<div class="uk-text-lead" id="Esss"><?=$emp->sss_id?></div>
					<div class="uk-text-meta">SSS ID</div>
				</div>
				<div>
					<div class="uk-text-lead" id="Eph"><?=$emp->ph_id?></div>
					<div class="uk-text-meta">PhilHealth ID</div>
				</div>
				<div>
					<div class="uk-text-lead" id="Epi"><?=$emp->pagibig_id?></div>
					<div class="uk-text-meta">PAG-IBIG ID</div>
				</div>
			</div>

			<h3 class="uk-heading-line uk-margin">
				<span>Work Details 
				[<button class="uk-button uk-button-text" uk-toggle="#modalWork">Edit</button>]</span>
			</h3>
			<div class="uk-grid-small uk-child-width-1-3@m" uk-grid>
				<div>
					<div class="uk-text-lead" id="Epos"><?=$emp->position?></div>
					<div class="uk-text-meta">Position</div>
				</div>
				<div>
					<div class="uk-text-lead" id="Edesig"><?=$emp->designation?></div>
					<div class="uk-text-meta">Designation</div>
				</div>
				<div>
					<div class="uk-text-lead" id="Egroup"><?=$emp->group_name?></div>
					<div class="uk-text-meta">Group</div>
				</div>
			</div>

			<h3 class="uk-heading-line uk-margin">
				<span>Emergerncy Details
				[<button class="uk-button uk-button-text" uk-toggle="#modalEmergency">Edit</button>]</span>
			</h3>
			<div class="uk-grid-small uk-child-width-1-2@m" uk-grid>
				<div>
					<div class="uk-text-lead" id="Eemername"><?=$emp->emername?></div>
					<div class="uk-text-meta">Name</div>
				</div>
				<div>
					<div class="uk-text-lead" id="Eemercontact"><?=$emp->emercontact?></div>
					<div class="uk-text-meta">Contact</div>
				</div>
			</div>
		</div>

		<div class="uk-width-1-3@m">
			<div class="uk-height-large uk-background-secondary uk-flex uk-flex-middle uk-flex-center uk-light" id="imgPreview">
				<?php if (empty($emp->emply_pic)): ?>
					<h1>Add Photo</h1>
				<?php else: ?>
					<img class="uk-width-1-1" src="<?=base_url().'images/employees/'.$emp->emply_pic?>">
				<?php endif ?>
			</div>
            <div class="uk-text-center">
                <div uk-form-custom="target: true">
                    <input type="file" name="image" onchange="showPreview(this)">
                    <input class="uk-input uk-form-width-medium" type="text" placeholder="Choose Photo" disabled>
                </div>
                <button class="uk-button uk-button-primary" id="addPic">Save</button>
            </div>
		</div>
	</div>
</div>

<div id="modalGeneral" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <h2 class="uk-modal-title">General Information</h2>
        <div class="uk-grid-small uk-child-width-1-3" uk-grid>
        	<div>
        		<label>First Name</label>
        		<input type="text" value="<?=$emp->emply_fname?>" name="fname" class="uk-width-1-1 uk-input uk-form-small">
        	</div>
        	<div>
        		<label>Middle Name</label>
        		<input type="text" value="<?=$emp->emply_mname?>" name="mname" class="uk-width-1-1 uk-input uk-form-small">
        	</div>
        	<div>
        		<label>Last Name</label>
        		<input type="text" value="<?=$emp->emply_lname?>" name="lname" class="uk-width-1-1 uk-input uk-form-small">
        	</div>
        </div>
        <div class="uk-grid-small uk-child-width-1-3" uk-grid>
        	<div>
        		<label>Gender</label>
        		<select name="gender" class="uk-width-1-1 uk-select uk-form-small">
        			<?php foreach ($sex as $ks => $vs): ?>
        			<?php if ($ks == $emp->sex): ?>
        				<option selected="selected" value="<?=$ks?>"><?=$vs?></option>
        			<?php else: ?>
        				<option value="<?=$ks?>"><?=$vs?></option>
        			<?php endif ?>	
        			<?php endforeach ?>
        		</select>
        	</div>
        	<div>
        		<label>Birth Date</label>
        		<input type="text" autocomplete="off" value="<?=$emp->bday?>" name="bday" class="uk-width-1-1 uk-input uk-form-small">
        	</div>
        	<div>
        		<label>Contact</label>
        		<input type="text" value="<?=$emp->contact?>" name="contact" class="uk-width-1-1 uk-input uk-form-small">
        	</div>
        </div>
        <p>
    		<label>Address</label>
    		<input type="text" value="<?=$emp->emply_address?>" name="address" class="uk-width-1-1 uk-input uk-form-small">
        </p>
        <p class="uk-text-right">
            <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
            <button class="uk-button uk-button-primary" id="btnGeneral" type="button">Save</button>
        </p>
    </div>
</div>

<div id="modalGov" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <h2 class="uk-modal-title">Government ID's</h2>
        <p>
    		<label>SSS</label>
    		<input type="text" value="<?=$emp->sss_id?>" name="sss" class="uk-width-1-1 uk-input uk-form-small">
        </p>
        <p>
    		<label>PhiHealth</label>
    		<input type="text" value="<?=$emp->ph_id?>" name="ph" class="uk-width-1-1 uk-input uk-form-small">
        </p>
        <p>
    		<label>PAG-IBIG</label>
    		<input type="text" value="<?=$emp->pagibig_id?>" name="pi" class="uk-width-1-1 uk-input uk-form-small">
        </p>
        <p class="uk-text-right">
            <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
            <button class="uk-button uk-button-primary" id="btnGov" type="button">Save</button>
        </p>
    </div>
</div>

<div id="modalWork" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <h2 class="uk-modal-title">Work Details</h2>
        <p>
    		<label>Position</label>
    		<input type="text" value="<?=$emp->position?>" name="pos" class="uk-width-1-1 uk-input uk-form-small">
        </p>
        <p>
    		<label>Designation</label>
    		<input type="text" value="<?=$emp->designation?>" name="desig" class="uk-width-1-1 uk-input uk-form-small">
        </p>
        <p>
    		<label>Group</label>
    		<select class="uk-select uk-form-small" name="group">
    			<?php foreach ($groups as $g): ?>
    			<?php if ($g['gID'] == $emp->emply_group): ?>
    				<option selected="selected" value="<?=$g['gID']?>"><?=$g['gName']?></option>
    			<?php else: ?>
    				<option value="<?=$g['gID']?>"><?=$g['gName']?></option>
    			<?php endif ?>
    			<?php endforeach ?>
    		</select>
        </p>
        <p class="uk-text-right">
            <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
            <button class="uk-button uk-button-primary" id="btnWork" type="button">Save</button>
        </p>
    </div>
</div>

<div id="modalEmergency" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <h2 class="uk-modal-title">Emergency Details</h2>
        <p>
    		<label>Name</label>
    		<input type="text" value="<?=$emp->emername?>" name="ename" class="uk-width-1-1 uk-input uk-form-small">
        </p>
        <p>
    		<label>Contact</label>
    		<input type="text" value="<?=$emp->emercontact?>" name="econtact" class="uk-width-1-1 uk-input uk-form-small">
        </p>
        <p class="uk-text-right">
            <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
            <button class="uk-button uk-button-primary" id="btnEmergency" type="button">Save</button>
        </p>
    </div>
</div>
    
<div class="uk-flex uk-flex-between" id="nexprevDiv"></div>
<script type="text/javascript">
	var eID = <?=$emp->emply_id?>;
	$(document).ready(function(){
		nextprev_employees(eID);

		$("[name=bday]").fdatepicker({format:'yyyy-mm-dd'});

		$("#restoreEmployee").click(function(){
			var data = {};
			data.emply = eID;
			if ("<?=$emp->emply_status?>" == 'TRUE') {
				data.stat = 'FALSE';
			} else {
				data.stat = 'TRUE';
			}

			if(!confirm("Are you sure you want to alter this data?")){ return false; }
			$.post({
				url: "<?=site_url('CBROS/ajax_requests/restore_employee/')?>",
				data: data,
				success: function (res) {
					if (data.stat == 'TRUE') {
						location.reload();
					} else {
						alert('Employee has been removed!');
						location.href = '<?=site_url('CBROS/employees')?>';
					}
				}
			});
		});

		$("#btnGeneral").click(function(){
			var data = {};
			data.emply = eID;
			data.act = 'general';
			data.fname = $("[name=fname]").val();
			data.mname = $("[name=mname]").val();
			data.lname = $("[name=lname]").val();
			data.sex = $("[name=gender]").val();
			data.contact = $("[name=contact]").val();
			data.bday = $("[name=bday]").val();
			data.address = $("[name=address]").val();
			$("#btnGeneral").attr('disabled','disabled').attr('uk-spinner','ratio:1');
			save_employee(data);
			UIkit.modal("#modalGeneral").hide();
			$("#btnGeneral").removeAttr('disabled').removeAttr('uk-spinner');
		});

		$("#btnGov").click(function(){
			var data = {};
			data.emply = eID;
			data.act = 'gov';
			data.sss = $("[name=sss]").val();
			data.ph = $("[name=ph]").val();
			data.pi = $("[name=pi]").val();

			$("#btnGov").attr('disabled','disabled').attr('uk-spinner','ratio:1');
			save_employee(data);
			UIkit.modal("#modalGov").hide();
			$("#btnGov").removeAttr('disabled').removeAttr('uk-spinner');
		});

		$("#btnWork").click(function(){
			var data = {};
			data.emply = eID;
			data.act = 'work';
			data.pos = $("[name=pos]").val();
			data.desig = $("[name=desig]").val();
			data.group = $("[name=group]").val();

			$("#btnWork").attr('disabled','disabled').attr('uk-spinner','ratio:1');
			save_employee(data);
			UIkit.modal("#modalWork").hide();
			$("#btnWork").removeAttr('disabled').removeAttr('uk-spinner');
		});

		$("#btnEmergency").click(function(){
			var data = {};
			data.emply = eID;
			data.act = 'emergency';
			data.name = $("[name=ename]").val();
			data.contact = $("[name=econtact]").val();

			$("#btnEmergency").attr('disabled','disabled').attr('uk-spinner','ratio:1');
			save_employee(data);
			UIkit.modal("#modalEmergency").hide();
			$("#btnEmergency").removeAttr('disabled').removeAttr('uk-spinner');
		});

		$("#addPic").click(function(){
			save_photo();
		});
	});

    function showPreview (image) {
        var reader = new FileReader();
        reader.onload = imageIsLoaded;
        reader.readAsDataURL(image.files[0]);
    }

    function imageIsLoaded (e) {
        var prev = $("<img src='"+e.target.result+"' class='uk-width-1-1'>");
        $("#imgPreview").html(prev);
    }

	function save_photo () {
		$("#addPic").removeAttr("disabled").removeAttr("uk-spinner");
 		var formData = new FormData();
 		formData.append('photo', $("[name=image]")[0].files[0]);
 		formData.append('emply', eID);
        $.ajax({
            url : "<?=site_url('CBROS/ajax_requests/upload_image')?>",
            type : 'POST',
            data : formData,
            cache : false,
            contentType : false,
            processData : false,
            success : function(res) {
				var r = JSON.parse(res);
				$("#addPic").removeAttr("disabled").removeAttr("uk-spinner");
				UIkit.notification({ message: r.msg, status: r.stat });
            }
        });
	}

	function get_employee () {
		var sex = {1:'Male',2:'Female'};
		$.post({
			url: "<?=site_url('CBROS/ajax_requests/get_employee/')?>",
			data: {emply:eID},
			success: function (res) {
				var r = JSON.parse(res);
				$("#Ename").text(r.name);
				$("#Esex").text(sex[r.sex]);
				$("#Ebday").text(r.nbday);
				$("#Eage").text(r.age);
				$("#Econtact").text(r.contact);
				$("#Eaddress").text(r.emply_address);
				$("#Esss").text(r.sss_id);
				$("#Eph").text(r.ph_id);
				$("#Epi").text(r.pagibig_id);
				$("#Epos").text(r.position);
				$("#Edesig").text(r.designation);
				$("#Egroup").text(r.group_name);
				$("#Eemername").text(r.emername);
				$("#Eemercontact").text(r.emercontact);
			}
		})
	}

	function save_employee (data) {
		$.post({
			url: "<?=site_url('CBROS/ajax_requests/update_employee/')?>",
			data: data,
			success: function (res) {
				get_employee();
				UIkit.notification({  message: 'Data updated successfully!', status: 'success' });
			},
			error: function (res) {
				UIkit.notification({  message: 'Something went wrong!', status: 'danger' });
			}
		});
	}

	function nextprev_employees (id) {
		var sorts = localStorage.getItem('sorts');
		if (!sorts) {
			var data = {sort:1, order:'asc', group:0};
		} else {
			var data = JSON.parse(sorts);
		}
		data.emply = id;
		data.stat = "<?=$emp->emply_status?>";
		console.log(data);

		$.post({
			url: "<?=site_url('CBROS/ajax_requests/get_nextprev_employees/')?>",
			data: data,
			success: function (res) {
				var r = JSON.parse(res);
				var div = '';

				if (r.prev) {
					var prev = r.prev;
					div += `<div>
							<a href="`+prev.id+`" class="uk-button uk-button-default">
								<i uk-icon="chevron-left"></i> Previous
							</a>
						</div>`;
				} else {
					div += `<div></div>`;
				}
				
				if (r.next) {
					var next = r.next;
					div += `<div>
							<a href="`+next.id+`" class="uk-button uk-button-default">
								Next <i uk-icon="chevron-right"></i> 
 							</a>
						</div>`;
				} else {
					div += `<div></div>`;
				}
				
				$("#nexprevDiv").html(div);
			}
		});
	}
</script>