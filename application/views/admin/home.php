<div class="uk-grid-small uk-child-width-1-2@m" uk-grid>
	<div class="uk-width-2-3@m">
		<div class="uk-padding-small uk-box-shadow-small uk-background-default uk-margin">
			<h3>Total Payments <?=$total?></h3>
			<div>
				<label class="uk-margin-small-right">View Summary</label>
				<div>
					<form action="<?=site_url('CBROS/print/summary_payouts/')?>" method="POST" id="printSummary">
			    		<label>Group</label>&nbsp;
			    		<select class="uk-select uk-form-small uk-form-width-medium uk-margin-small-right" name="inputgroupSummary">
			    			<option value="0">All</option>
			    			<?php foreach ($countG as $g): ?>
			    				<option value="<?=$g['gID']?>"><?=$g['gName']?></option>
			    			<?php endforeach ?>
			    		</select>
			    		<label>Date</label>&nbsp;
						<input type="text" name="inputdateSummary" class="uk-input uk-form-small uk-form-width-small" autocomplete="off">
						<button class="uk-button uk-button-primary uk-button-small">Go</button>
					</form>
				</div>
			</div>
		</div>

		<div class="uk-padding-small uk-box-shadow-small uk-background-default">
			<h3>Payment Summary Bar Chart <span id="spanYear"></span>

				<div class="uk-float-right">
					<input type="text" name="inputyear" class="uk-input uk-form-small uk-form-width-xsmall">
					<button class="uk-button uk-button-primary uk-button-small" id="btnYear">Go</button>
				</div>
			</h3>
			<div id="graphDiv"></div>
		</div>
	</div>
	<div class="uk-width-1-3@m">
		<div class="uk-padding-small uk-box-shadow-small uk-background-default">
			<table class="uk-table uk-table-small uk-text-small uk-margin-remove">
				<tr class="uk-text-lead">
					<td class="uk-text-bold uk-table-shrink"><?=number_format($countE)?></td>
					<td>Total of Employees</td>
				</tr>
				<?php foreach ($countG as $g): ?>
					<tr>
						<td class="uk-table-shrink"><?=number_format($g['count'])?></td>
						<td><?=$g['gName']?></td>
					</tr>
				<?php endforeach ?>
			</table>
		</div>
	</div>
</div>
<script type="text/javascript">
	var yr = <?=date('Y')?>; 

	$(document).ready(function(){
		var yf = (Number(yr) + Number(2));

		$("[name=inputyear]").fdatepicker({
									initialDate: "<?=date('Y')?>",
									format: 'yyyy',
									startView: 4,
									minView: 4,
									maxView: 4,
									endDate: 'yf',
									startDate: '2017'
								});


		$("[name=inputdateSummary]").fdatepicker({
									initialDate: "<?=date('Y-m')?>",
									format: 'yyyy-mm',
									startView: 3,
									minView: 3,
									maxView: 3,
									startDate: '2017-01'
								});

		get_summary(yr);

		$("#btnYear").click(function(){
			var year = $("[name=inputyear]").val();
			get_summary(year);
		});

		$("#printSummary").submit(function(){
			window.open('', 'printSummary', 'width=1000,height=660,scrollbars=yes,menubar=no,status=yes,resizable=yes,screenx=0,screeny=0'); 
			this.target = 'printSummary';
		});

	});

	function get_summary (year) {
		$.post({
			url:"<?=site_url('CBROS/ajax_requests/get_payout_summary/')?>",
			data: {year:year},
			success: function (res) {
				var r = JSON.parse(res);
				var m = r.max.toString();
				$("#spanYear").text(year);

				simpleBarChart({
					div: '#graphDiv',
					cols: r.data,
					rows: r.max
				});
			}
		});
	}
	
	function simpleBarChart (data) {
		var max = (!data.rows) ? 50 : data.rows;
		var m = max.toString();
		var rows = m.substring(1,0);
			rows = Number(rows) + Number(1);

		 for (var i = 2; i <= m.length; i++) {
		 	rows += '0';
		 }

		var cols = (!data.cols) ? {1:10,2:20,3:30,4:40,5:50} : data.cols;
		var color = (!data.color) ? '#1e87f0' : data.color;
		var html = data.div;
		var divident = (!data.divident) ? 10 : data.divident;

		var num = rows / divident;
		var div = `<div style="border: solid 1px rgba(0,0,0,0.5); padding: 10px; padding-top: 20px; font-size: 12px; font-family: monospace">
					<div style="position: relative; height: 300px; border-bottom: solid 1px rgba(0,0,0,0.3);">`;

				for (var i = rows; i >= 1; i-= Number(num)) {
					var perc = (i/rows) * 100;
					div += `<div style="border-top:solid 1px rgba(0,0,0,0.3); position: absolute; width: 100%; height: `+perc+`%; bottom: 0px;">
								`+num_format(i)+`
							</div>`;
				}

				div += `<div style="display:flex; justify-content: space-around; align-items: flex-end; height: 100%;">
							<div style="padding-left: 50px;">&nbsp;</div>`;

				$.each(cols, function(ck, cv){
					var perc = (cv/rows) * 100;
					div += `<div style="width: 20px; background-color: `+color+`; height: `+perc+`%; position: relative;;">
								<div style="position:absolute; top: -18px; text-align: center; z-index: 1; text-shadow: 1px 2px 0px #fff; color: #000;">`+num_format(cv)+`</div>
							</div>`;
				});

				div += `</div>`;

		div += `</div>`;

				div += `<div style="display:flex; justify-content: space-around; align-items: flex-end;">
							<div style="padding-left: 50px;">&nbsp;</div>`;

				$.each(cols, function(ck, cv){
					div += `<div style="width: 20px; text-align: center;">
							`+ck+`</div>`;
				});

				div += `</div>`;

		div += `</div>`;

		$(html).html(div);
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