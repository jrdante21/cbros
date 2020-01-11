<div class="uk-padding-small uk-box-shadow-small uk-background-default">
	<table class="uk-table uk-table-small uk-text-small uk-margin-remove uk-table-divider border uk-table-hover">
		<thead class="uk-alert">
			<th>Group Name</th>
			<th>No. of Employees</th>
			<th>Pay Due</th>
			<th></th>
		</thead>
		<tbody id="tbody"></tbody>
	</table>
</div>

<div id="modalDate" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <h2 class="uk-modal-title" id="headDate"></h2>
        <div class="uk-grid-small uk-child-width-1-2" uk-grid>
        	<div>
        		<label>Date Start</label>
        		<input type="text" name="dateS" class="uk-width-1-1 uk-input uk-form-small">
        	</div>
        	<div>
        		<label>Date End</label>
        		<input type="text" name="dateE" class="uk-width-1-1 uk-input uk-form-small">
        	</div>
        </div>
        <p class="uk-text-right">
            <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
            <button class="uk-button uk-button-primary" id="btnDate" type="button">Save</button>
        </p>
    </div>
</div>

<div id="modalGName" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <h2 class="uk-modal-title" id="headGname"></h2>
        <p>
    		<label>Group Name</label>
    		<input type="text" name="gName" class="uk-width-1-1 uk-input uk-form-small">
        </p>
        <p class="uk-text-right">
            <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
            <button class="uk-button uk-button-primary" id="btnGName" type="button">Save</button>
        </p>
    </div>
</div>

<div id="modalDelete" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <h2 class="uk-modal-title" id="headDelete"></h2>
        <p>
    		Are you sure you want to delete this?
        </p>
        <p class="uk-text-right">
            <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
            <button class="uk-button uk-button-danger" id="btnDelete" type="button">Delete</button>
        </p>
    </div>
</div>

<script type="text/javascript">
	var groups;
	var groupID;

	$(document).ready(function(){
		$("[name=dateS]").fdatepicker({format:'yyyy-mm-dd'});
		$("[name=dateE]").fdatepicker({format:'yyyy-mm-dd'});
		get_groups();

		$("#btnDate").click(function(){
			var data = {};
			data.act = 'date';
			data.ds = $("[name=dateS]").val();
			data.de = $("[name=dateE]").val();
			data.gID = groupID;

			$("#btnDate").attr('disabled','disabled').attr('uk-spinner','ratio:1');
			update_group(data);
			$("#btnDate").removeAttr('disabled').removeAttr('uk-spinner');
			UIkit.modal("#modalDate").hide();
		});

		$("#btnGName").click(function(){
			var data = {};
			data.act = 'name';
			data.name = $("[name=gName]").val();
			data.gID = groupID;

			$("#btnGName").attr('disabled','disabled').attr('uk-spinner','ratio:1');
			update_group(data);
			$("#btnGName").removeAttr('disabled').removeAttr('uk-spinner');
			UIkit.modal("#modalGName").hide();
		});

		$("#btnDelete").click(function(){
			$("#btnDelete").attr('disabled','disabled').attr('uk-spinner','ratio:1');
			$.post({
				url: "<?=site_url('CBROS/ajax_requests/delete_group/')?>",
				data: {gID: groupID},
				success: function (res) {
					var r = JSON.parse(res);
					UIkit.notification({  message: r.msg, status: r.stat});
					$("#btnDelete").removeAttr('disabled').removeAttr('uk-spinner');
					UIkit.modal("#modalDelete").hide();
					get_groups();
				}
			});
		});
	});

	function update_group (data) {
		$.post({
			url: "<?=site_url('CBROS/ajax_requests/update_group/')?>",
			data: data,
			success: function (res) {
				get_groups();
				UIkit.notification({  message: 'Data updated successfully!', status: 'success' });
			},
			error: function (res) {
				UIkit.notification({  message: 'Something went wrong!', status: 'danger' });
			}
		});
	}
	
	function get_groups () {
		$("#tbody").html(`<tr><td colspan="4" align="center"><i uk-spinner></i></td></tr>`);
		$.get({
			url: "<?=site_url('CBROS/ajax_requests/get_groups/')?>",
			success: function (res) {
				var r = JSON.parse(res);
				groups = r;
				var tr = '';
				$.each(r, function (k,v){
					tr += `<tr>
							<td>`+v.gName+`
								<button class="uk-button uk-button-text uk-margin-small-left" onclick="show_gname(`+v.gID+`)">[ edit ]</button>
							</td>
							<td>`+v.count+`
								<button class="uk-button uk-button-text uk-margin-small-left" onclick="go_to_employees(`+v.gID+`)">[ view ]</button>
							</td>
							<td>`+v.ds+`&nbsp;-&nbsp;`+v.de+`
								<button class="uk-button uk-button-text uk-margin-small-left" onclick="show_paydue(`+v.gID+`)">[ edit ]</button>
							</td>
							<td align="right">
								<button class="uk-button uk-button-text uk-text-danger" onclick="delete_group(`+v.gID+`)">[remove]</button>
							</td>
						</tr>`;
				});
				$("#tbody").html(tr);
			}
		})
	}

	function delete_group (gID) {
		$.each(groups, function(k,v){
			if (v.gID == gID) {
				$("#headDelete").html(v.gName);
			}
		});
		groupID = gID;
		UIkit.modal("#modalDelete").show();
	}

	function show_paydue (gID) {
		$.each(groups, function(k,v){
			if (v.gID == gID) {
				$("#headDate").html(v.gName);
				$("[name=dateS]").val(v.date_start);
				$("[name=dateE]").val(v.date_end);
			}
		});
		groupID = gID;
		UIkit.modal("#modalDate").show();
	}

	function show_gname (gID) {
		$.each(groups, function(k,v){
			if (v.gID == gID) {
				$("#headGname").html(v.gName);
				$("[name=gName]").val(v.gName);
			}
		});
		groupID = gID;
		UIkit.modal("#modalGName").show();
	}

	function go_to_employees (gID) {
		var data = {sort:1, order:'asc', group:gID};
		localStorage.setItem('sorts', JSON.stringify(data));
		window.open("<?=site_url('CBROS/employees')?>",'_self');
	}
</script>