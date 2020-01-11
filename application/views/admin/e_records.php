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
				<button class="uk-button uk-button-small uk-button-primary" id="btnDate">Go</button>
			</div>
		</div>
		<div class="uk-text-right" style="font-family: monospace;">
			<div class="uk-text-lead" id="spanAmount"></div>
			<div class="uk-text-meta">Total Net Pay</div>
		</div>
	</div>
	<div class="uk-overflow-auto">
		<table class="uk-table uk-table-small uk-table-divider uk-text-small border uk-text-nowrap uk-text-right" style="font-family: monospace;">
			<colgroup>
				<col></col>
				<col span="6" style="background:rgb(255,255,185)"></col>
				<col span="5" style="background:rgb(185,255,185)"></col>
				<col span="8" style="background:rgb(255,200,200)"></col>
			</colgroup>
			<thead>
				<th>Date Periods</th>
				<th>Shop Rate</th>
				<th>Rate</th>
				<th>OT Rate</th>
				<th>Shop Days</th>
				<th>Work Days</th>
				<th>Over Time</th>
				<th>Work Pay</th>
				<th>OT Pay</th>
				<th>Shop Pay</th>
				<th>Bonus</th>
				<th>Incentives</th>
				<th>Tax</th>
				<th>SSS</th>
				<th>PhilHealth</th>
				<th>PAG-IBIG</th>
				<th>Groceries</th>
				<th>Loans</th>
				<th>Savings</th>
				<th>Advance</th>
				<th>Net Pay</th>
			</thead>
			<tbody id="tbody"></tbody>
		</table>
	</div>
</div>
    
<div class="uk-flex uk-flex-between" id="nexprevDiv"></div>
<script type="text/javascript">
	var eID = <?=$emp->emply_id?>;
	var sDate;
	var eDate;

	$(document).ready(function(){
		nextprev_employees(eID);
		$("[name=dateS]").fdatepicker({format:'yyyy-mm-dd'});
		$("[name=dateE]").fdatepicker({format:'yyyy-mm-dd'});
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
	});

	function paginate (n) {
		get_records(n);
	}

	function get_records (n = 0) {
		$("#tbody").html(`<tr><td colspan="21" align="left"><i uk-spinner></i></td></tr>`);
		$.post({
			url: "<?=site_url('CBROS/ajax_requests/get_records/')?>"+n,
			data: {emply:eID, ds:sDate, de:eDate},
			success: function (res) {
				var r = JSON.parse(res);
				var c = r.comp;
				var tr = '';

				var gross = Number(c.wp) + Number(c.otp) + Number(c.sp) + Number(c.bonus) + Number(c.incent);

				$.each(r.data, function(k,v){
					tr += `<tr>
							<td>`+v.ds+`&nbsp;-&nbsp;`+v.de+`</td>
							<td>`+num_format(v.shoprate)+`</td>
							<td>`+num_format(v.rate)+`</td>
							<td>`+num_format(v.otrate)+`</td>
							<td>`+num_format(v.shopdays)+`</td>
							<td>`+num_format(v.wd)+`</td>
							<td>`+num_format(v.ot)+`</td>
							<td>`+num_format(v.wp)+`</td>
							<td>`+num_format(v.otp)+`</td>
							<td>`+num_format(v.sp)+`</td>
							<td>`+num_format(v.bonus)+`</td>
							<td>`+num_format(v.incentives)+`</td>
							<td>`+num_format(v.tax)+`</td>
							<td>`+num_format(v.sss)+`</td>
							<td>`+num_format(v.ph)+`</td>
							<td>`+num_format(v.pagibig)+`</td>
							<td>`+num_format(v.groceries)+`</td>
							<td>`+num_format(v.loans)+`</td>
							<td>`+num_format(v.savings)+`</td>
							<td>`+num_format(v.advance)+`</td>
							<td>`+num_format(Number(v.gross) - Number(v.deducts))+`</td>
						</tr>`;
				});

				if (r.link != '') {
					tr += `<tr><td colspan="21" align="center">`+r.link+`</td></tr>`;
				}
				
				tr += `<tr class="uk-text-bold">
							<td colspan="7" align="left">Total</td>
							<td>`+num_format(c.wp)+`</td>
							<td>`+num_format(c.otp)+`</td>
							<td>`+num_format(c.sp)+`</td>
							<td>`+num_format(c.bonus)+`</td>
							<td>`+num_format(c.incent)+`</td>
							<td>`+num_format(c.tax)+`</td>
							<td>`+num_format(c.sss)+`</td>
							<td>`+num_format(c.ph)+`</td>
							<td>`+num_format(c.pi)+`</td>
							<td>`+num_format(c.grc)+`</td>
							<td>`+num_format(c.loans)+`</td>
							<td>`+num_format(c.sav)+`</td>
							<td>`+num_format(c.adv)+`</td>
							<td>`+num_format(Number(gross) - Number(c.deduct))+`</td>
						</tr>`;
				$("#spanAmount").text(num_format(Number(gross) - Number(c.deduct)));

				if (r.length <= 0) {
					$("#tbody").html(`<tr><td colspan="21" align="left">No data found...</td></tr>`);
				} else {
					$("#tbody").html(tr);
				}
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