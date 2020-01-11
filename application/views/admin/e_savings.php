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
<div class="uk-padding-small uk-box-shadow-small uk-background-default">
	<h3><?=$emp->emply_id?> | <?=$emp->emply_fname.' '.$emp->emply_mname.' '.$emp->emply_lname?></h3>
	<div class="uk-flex uk-flex-between">
		<div class="uk-flex uk-flex-bottom uk-margin-bottom">
			<div>
				<label>Date To</label><br>
				<input type="input" name="dateS" class="uk-input uk-form-small uk-form-width-small">
			</div>
			<div>
				<label>Date From</label><br>
				<input type="input" name="dateE" class="uk-input uk-form-small uk-form-width-small">
			</div>
			<div>
				<button class="uk-button uk-button-small uk-button-primary uk-margin-small-right" id="btnDate">Go</button>
				<button class="uk-button uk-button-small uk-button-primary" uk-toggle="#modalSavings">Add Savings</button>
			</div>
		</div>
		<div class="uk-text-right" style="font-family: monospace;">
			<div class="uk-text-lead" id="spanAmount"></div>
			<div class="uk-text-meta">Total Amount</div>
		</div>
	</div>
	<div class="uk-overflow-auto">
		<table class="uk-table uk-table-small uk-table-divider uk-text-small border uk-text-nowrap" style="font-family: monospace;">
			<thead>
				<th>Date</th>
				<th>Amount</th>
				<th></th>
			</thead>
			<tbody id="tbody"></tbody>
		</table>
	</div>
</div>

<div id="modalSavings" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <h2 class="uk-modal-title">Add Savings</h2>
        <p>
    		<label>Amount</label>
    		<input type="number" name="amount" step="0.01" class="uk-input uk-width-1-1 uk-form-small">
        </p>
        <p>
    		<label>Date</label>
    		<input type="text" name="dateA" class="uk-input uk-width-1-1 uk-form-small" autocomplete="off">
        </p>
        <p class="uk-text-right">
            <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
            <button class="uk-button uk-button-primary" id="btnAddSavings" type="button">Save</button>
        </p>
    </div>
</div>

<div class="uk-flex uk-flex-between" id="nexprevDiv"></div>
<script type="text/javascript">
	var eID = <?=$emp->emply_id?>;
	var gID = <?=$emp->group_id?>;
	var sDate;
	var eDate;
	var row = 0;

	$(document).ready(function(){
		nextprev_employees(eID);
		$("[name=dateS]").fdatepicker({format:'yyyy-mm-dd'});
		$("[name=dateE]").fdatepicker({format:'yyyy-mm-dd'});
		$("[name=dateA]").fdatepicker({format:'yyyy-mm-dd'});
		get_records();

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
					location.reload();
				}
			});
		});

		$("#btnDate").click(function(){
			sDate = $("[name=dateS]").val();
			eDate = $("[name=dateE]").val();
			get_records();
		});

		$("#btnAddSavings").click(function(){
			var data = {};
			data.emply = eID;
			data.group = gID;
			data.amount = $("[name=amount]").val();
			data.date = $("[name=dateA]").val();

			$("#btnAddSavings").attr("disabled","disabled").attr("uk-spinner","ratio:1");
			$.post({
				url: "<?=site_url('CBROS/ajax_requests/add_savings/')?>",
				data: data,
				success: function (res) {
					$("#btnAddSavings").removeAttr("disabled").removeAttr("uk-spinner");
					var r = JSON.parse(res);
					UIkit.notification({  message: r.msg, status: r.stat });

					if (r.stat == 'success') {
						UIkit.modal("#modalSavings").hide();
						get_records();
					}
				}
			});
		});
	});

	function paginate (n) {
		row = n;
		get_records();
	}

	function get_records () {
		$.post({
			url: "<?=site_url('CBROS/ajax_requests/get_savings/')?>"+row,
			data: {emply:eID, ds:sDate, de:eDate},
			success: function (res) {
				var r = JSON.parse(res);
				var tr = '';
				$("#spanAmount").text(num_format(r.comp));

				$.each(r.data, function(k,v){
					tr += `<tr>
							<td>`+v.dt+`</td>
							<td>`+num_format(v.amount)+`</td>
							<td align="right">
								<button class="uk-button uk-button-text uk-text-danger" onclick="delete_data(`+v.savings_id+`)">[remove]</button>
							</td>
						</tr>`;
				});

				if (r.link != '') {
					tr += `<tr><td colspan="3" align="center">`+r.link+`</td></tr>`;
				}
				
				if (r.data.length <= 0) {
					$("#tbody").html(`<tr><td colspan="3" align="left">No data found...</td></tr>`);
				} else {
					$("#tbody").html(tr);
				}
			}
		});
	}

	function delete_data (id) {
		if (!confirm('Are you sure you want to delete this?')) {
			return false;
		} 

		$.post({
			url: "<?=site_url('CBROS/ajax_requests/delete_savings/')?>",
			data: {id:id, group:gID},
			success: function (res) {
				var r = JSON.parse(res);
				UIkit.notification({  message: r.msg, status: r.stat });
				get_records();
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

		$.post({
			url: "<?=site_url('CBROS/ajax_requests/get_nextprev_employees/')?>",
			data: data,
			success: function (res) {
				var r = JSON.parse(res);
				var div = '';
				console.log(r);

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

	function num_format (num) {
		num += '';
		var x = num.split('.');
		var x1 = x[0];
		var x2 = x.length > 1 ? '.' + x[1] : '';
		var x3 = /(\d+)(\d{3})/;
		while (x3.test(x1)) {
			x1 = x1.replace(x3, '$1' + ',' + '$2');
		}
		return x1 + x2;
	}
</script>