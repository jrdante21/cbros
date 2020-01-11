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
	<h3>
		<?=$emp->emply_id?> | <?=$emp->emply_fname.' '.$emp->emply_mname.' '.$emp->emply_lname?>
		<button class="uk-button uk-button-primary uk-float-right" id="printPayslip">Print</button>
	</h3>
	<div class="uk-grid-small uk-child-width-1-2@m uk-margin" uk-grid style="font-family: monospace;">
		<div>
			<div class="uk-text-lead"><?=$emp->ds?> - <?=$emp->de?></div>
			<div class="uk-text-meta">Duedate Pay</div>
		</div>
		<div class="uk-text-right">
			<div class="uk-text-lead" id="spanNET"></div>
			<div class="uk-text-meta">Total NET</div>
		</div>
	</div>
	<div class="uk-grid-small uk-child-width-1-2@m" uk-grid>
		<div>
			<table class="uk-table uk-table-small uk-text-small uk-table-divider border uk-table-hover uk-text-nowrap" style="font-family: monospace;">
				<thead class="uk-alert">
					<th colspan="5">Work Status & Earnings</th>
				</thead>
				<thead>
					<th width="10%"></th>
					<th class="uk-text-right">Rate</th>
					<th class="uk-text-right">Days / Time</th>
					<th class="uk-text-right">Total</th>
					<th class="uk-table-shrink"></th>
				</thead>
				<tr>
					<td>Work Pay</td>
					<td align="right" id="spanWR"></td>
					<td align="right" id="spanWD"></td>
					<td align="right" id="spanWT"></td>
					<td align="right">
						<button class="uk-button uk-button-text ws-btn" value="wp">edit</button>
					</td>
				</tr>
				<tr>
					<td>OT Pay</td>
					<td align="right" id="spanOTR"></td>
					<td align="right" id="spanOTD"></td>
					<td align="right" id="spanOTT"></td>
					<td align="right">
						<button class="uk-button uk-button-text ws-btn" value="otp">edit</button>
					</td>
				</tr>
				<tr>
					<td>Shop Pay</td>
					<td align="right" id="spanSR"></td>
					<td align="right" id="spanSD"></td>
					<td align="right" id="spanST"></td>
					<td align="right">
						<button class="uk-button uk-button-text ws-btn" value="sp">edit</button>
					</td>
				</tr>
				<thead class="uk-alert">
					<th colspan="5" width="10%">Others</th>
				</thead>
				<tr>
					<td>Bonus</td>
					<td align="right" colspan="3" id="spanBonus"></td>
					<td align="right">
						<button class="uk-button uk-button-text others-btn" value="bonus">edit</button>
					</td>
				</tr>
				<tr>
					<td>Incentives</td>
					<td align="right" colspan="3" id="spanIncent"></td>
					<td align="right">
						<button class="uk-button uk-button-text others-btn" value="incent">edit</button>
					</td>
				</tr>
				<thead class="uk-alert-primary uk-text-bold">
					<td>Total</td>
					<td align="right" colspan="3" id="spanGross"></td>
					<td></td>
				</thead>
			</table>
		</div>
		<div>
			<table class="uk-table uk-table-small uk-text-small uk-table-divider border uk-table-hover uk-text-nowrap" style="font-family: monospace;">
				<thead class="uk-alert">
					<th colspan="5" width="10%">Deductions</th>
				</thead>
				<tr>
					<td width="10%">Cash Advance</td>
					<td align="right" colspan="3" class="uk-table-expand" id="spanADV"></td>
					<td></td>
				</tr>
				<tr>
					<td width="10%">Savings</td>
					<td align="right" colspan="3" id="spanSAV"></td>
					<td></td>
				</tr>
				<tr>
					<td width="10%">With-holding TAX</td>
					<td align="right" colspan="3" id="spanTAX"></td>
					<td align="right">
						<button class="uk-button uk-button-text others-btn" value="tax">edit</button>
					</td>
				</tr>
				<tr>
					<td width="10%">SSS</td>
					<td align="right" colspan="3" id="spanSSS"></td>
					<td align="right">
						<button class="uk-button uk-button-text others-btn" value="sss">edit</button>
					</td>
				</tr>
				<tr>
					<td width="10%">PhilHealth</td>
					<td align="right" colspan="3" id="spanPH"></td>
					<td align="right">
						<button class="uk-button uk-button-text others-btn" value="ph">edit</button>
					</td>
				</tr>
				<tr>
					<td width="10%">PAG-IBIG</td>
					<td align="right" colspan="3" id="spanPI"></td>
					<td align="right">
						<button class="uk-button uk-button-text others-btn" value="pagibig">edit</button>
					</td>
				</tr>
				<tr>
					<td width="10%">Groceries</td>
					<td align="right" colspan="3" id="spanGRC"></td>
					<td align="right">
						<button class="uk-button uk-button-text others-btn" value="grc">edit</button>
					</td>
				</tr>
				<tr>
					<td width="10%">Loans</td>
					<td align="right" colspan="3" id="spanLNS"></td>
					<td align="right">
						<button class="uk-button uk-button-text others-btn" value="loans">edit</button>
					</td>
				</tr>
				<thead class="uk-alert-warning uk-text-bold">
					<td>Total</td>
					<td align="right" colspan="3" id="spanDeducts"></td>
					<td></td>
				</thead>
			</table>
		</div>
	</div>
	<div style="font-family: monospace;">
		<span class="uk-text-meta">Date Modified:</span> <span id="dateMod"></span>
	</div>
</div>

<div id="modalWP" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <h2 class="uk-modal-title" id="headWP"></h2>
        <p>
        	<label>Rate</label>
        	<input type="number" name="ratePay" step="0.01" class="uk-input uk-width-1-1 uk-form-small">
        </p>
        <p>
        	<label>Days / Time</label>
        	<input type="number" name="rateTime" step="0.01" class="uk-input uk-width-1-1 uk-form-small">
        </p>
        <p class="uk-text-right">
            <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
            <button class="uk-button uk-button-primary" id="btnWP" type="button">Save</button>
        </p>
    </div>
</div>

<div id="modalOTHERS" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <h2 class="uk-modal-title" id="headOTHERS"></h2>
        <p>
        	<label>Amount</label>
        	<input type="number" name="rateOTHERS" step="0.01" class="uk-input uk-width-1-1 uk-form-small">
        </p>
        <p class="uk-text-right">
            <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
            <button class="uk-button uk-button-primary" id="btnOTHERS" type="button">Save</button>
        </p>
    </div>
</div>

<div class="uk-flex uk-flex-between" id="nexprevDiv"></div>
<script type="text/javascript">
	var eID = <?=$emp->emply_id?>;
	var sDate = '<?=$emp->date_start?>';
	var eDate = '<?=$emp->date_end?>';
	var Pay;

	$(document).ready(function(){
		get_last_records();
		nextprev_employees(eID);
		
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

		$(".ws-btn").click(function(){
			var act = $(this).val();
			var rate = 0;
			var time = 0;
			var head = '';

			switch (act) {
				case 'wp':
					rate = Pay.rate;
					time = Pay.wd;
					head = 'Work Pay';
					break;

				case 'otp':
					rate = Pay.otrate;
					time = Pay.ot;
					head = 'Over Time Pay';
					break;

				case 'sp':
					rate = Pay.srate;
					time = Pay.sd;
					head = 'Shop Pay';
					break;
			}

			$("#btnWP").val(act);
			$("#headWP").html(head);
			$("[name=ratePay]").val(rate);
			$("[name=rateTime]").val(time);
			UIkit.modal("#modalWP").show();
		});

		$(".others-btn").click(function(){
			var act = $(this).val();
			var rate = 0;
			var head = '';

			switch (act) {
				case 'bonus':
					rate = Pay.bonus;
					head = 'Bonus';
					break;

				case 'incent':
					rate = Pay.incent;
					head = 'Incentives';
					break;

				case 'tax':
					rate = Pay.tax;
					head = 'With-holding TAX';
					break;

				case 'sss':
					rate = Pay.sss;
					head = 'SSS';
					break;

				case 'ph':
					rate = Pay.ph;
					head = 'PhilHealth';
					break;

				case 'pagibig':
					rate = Pay.pagibig;
					head = 'PAG-IBIG';
					break;

				case 'grc':
					rate = Pay.grc;
					head = 'Groceries';
					break;

				case 'loans':
					rate = Pay.loans;
					head = 'Loans';
					break;

			}

			$("#btnOTHERS").val(act);
			$("#headOTHERS").html(head);
			$("[name=rateOTHERS]").val(rate);
			UIkit.modal("#modalOTHERS").show();
		});

		$("#btnWP").click(function(){
			var act = $(this).val();
			var rate = $("[name=ratePay]").val();
			var time = $("[name=rateTime]").val();
			switch (act) {
				case 'wp':
					Pay.rate = rate;
					Pay.wd = time;
					break;

				case 'otp':
					Pay.otrate = rate;
					Pay.ot = time;
					break;

				case 'sp':
					Pay.srate = rate;
					Pay.sd = time;
					break;
			}

			$("#btnWP").attr("disabled","disabled").attr("uk-spinner","ratio:1");
			update_payslip();
			$("#btnWP").removeAttr("disabled").removeAttr("uk-spinner");
			UIkit.modal("#modalWP").hide();
		});

		$("#btnOTHERS").click(function(){
			var act = $(this).val();
			var rate = $("[name=rateOTHERS]").val();
			Pay[act] = rate;
			
			$("#btnOTHERS").attr("disabled","disabled").attr("uk-spinner","ratio:1");
			update_payslip();
			$("#btnOTHERS").removeAttr("disabled").removeAttr("uk-spinner");
			UIkit.modal("#modalOTHERS").hide();
		});

		$("#printPayslip").click(function(){
			window.open('<?=site_url('CBROS/print/employee_payslip/')?>'+eID, 'printPayslip', 'width=1000,height=660,scrollbars=yes,menubar=no,status=yes,resizable=yes,screenx=0,screeny=0'); 
			return false;
		});

	});

	function update_payslip () {
		$.post({
			url: "<?=site_url('CBROS/ajax_requests/update_payslip/')?>",
			data: {emply:eID, start:sDate, end:eDate, data:Pay},
			success: function (res) {
				UIkit.notification({  message: 'Payslip updated successfully!', status: 'success' });
				get_last_records();
			},
			error: function (res) {
				UIkit.notification({  message: 'Something went wrong!', status: 'danger' });
			}
		})
	}

	function get_last_records () {
		$.post({
			url: "<?=site_url('CBROS/ajax_requests/get_last_records/')?>",
			data: {emply:eID, start:sDate, end:eDate},
			success: function (res) {
				var r = JSON.parse(res);
				Pay = r;
				$("#dateMod").text(r.date_mod);

				// Work Pay
				$("#spanNET").text(num_format(Number(r.gross) - Number(r.deducts)));
				$("#spanWR").text(num_format(r.rate));
				$("#spanWD").text(num_format(r.wd));
				$("#spanWT").text(num_format(r.wp));

				//OT Pay
				$("#spanOTR").text(num_format(r.otrate));
				$("#spanOTD").text(num_format(r.ot));
				$("#spanOTT").text(num_format(r.otp));

				//Shop Pay
				$("#spanSR").text(num_format(r.srate));
				$("#spanSD").text(num_format(r.sd));
				$("#spanST").text(num_format(r.sp));

				//bonus
				$("#spanBonus").text(num_format(r.bonus));

				//Incentives
				$("#spanIncent").text(num_format(r.incent));

				//Gross
				$("#spanGross").text(num_format(r.gross));

				//Deductions
				$("#spanADV").text(num_format(r.adv));
				$("#spanSAV").text(num_format(r.savings));
				$("#spanTAX").text(num_format(r.tax));
				$("#spanSSS").text(num_format(r.sss));
				$("#spanPH").text(num_format(r.ph));
				$("#spanPI").text(num_format(r.pagibig));
				$("#spanGRC").text(num_format(r.grc));
				$("#spanLNS").text(num_format(r.loans));
				$("#spanDeducts").text(num_format(r.deducts));
			}
		})
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