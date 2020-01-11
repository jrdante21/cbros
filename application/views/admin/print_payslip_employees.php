<!DOCTYPE html>
<html>
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
<style type="text/css">
	table { font-family: monospace !important; font-size: 16px; }
	.dashed { border-top: dashed 1px rgba(0,0,0,0.5); padding-top: 5px;}
	@media print {
		#printDiv > div { height: 50%; width: 100%; margin-bottom: 0px; }
		img {display: block;}
	}
</style>
<body>
	<div id="printDiv">
		<?php foreach ($rec as $r): ?>
			<div>
				<table style="width: 100%; height: 50% !important;">
					<tr>
						<?php for ($i=1; $i <= 2; $i++) { ?> 
						<td width="50%" class="border uk-padding-small">
							<table width="100%">
								<tr>
									<td width="8%" align="center">
										<img src="<?=base_url();?>images/cbros-logo.png">
									</td>
									<td width="92%" class="uk-text-uppercase" align="center">
										<b><big>Payslip</big></b><br>
										c-bros construction and sam's mountain view ridge resort and hotel
									</td>
								</tr>
							</table>
							<table width="100%">
								<tr><td colspan="4" style="height: 10px"></td></tr>
								<tr><td colspan="4" class="uk-text-bold">Employee Information</td></tr>
								<tr>
									<td width="20%">Name</td>
									<td width="30%">: <?=$r['emply_fname']?> <?=$r['emply_mname']?> <?=$r['emply_lname']?></td>
									<td width="20%">ID</td>
									<td width="30%">: <?=$r['emply_id']?></td>
								</tr>
								<tr>
									<td>Address</td>
									<td>: <?=$r['emply_address']?></td>
									<td>Position</td>
									<td>: <?=$r['position']?></td>
								</tr>
								<tr>
									<td>Group</td>
									<td>: <?=$r['group_name']?></td>
									<td>PhilHealth ID</td>
									<td>: <?=$r['ph_id']?></td>
								</tr>
								<tr>
									<td>SSS ID</td>
									<td>: <?=$r['sss_id']?></td>
									<td>PAG-IBIG ID</td>
									<td>: <?=$r['pagibig_id']?></td>
								</tr>
								<tr><td colspan="4" class="dashed"></td></tr>
								<tr><td colspan="4" class="uk-text-bold">Pay Status</td></tr>
								<tr>
									<td>Pay Date Start</td>
									<td>: <?=$r['ds']?></td>
									<td>Pay Date End</td>
									<td>: <?=$r['de']?></td>
								</tr>
								<tr>
									<td>Rate per Day</td>
									<td>: <?=number_format($r['rate'],2)?></td>
									<td>Work Days</td>
									<td>: <?=number_format($r['wd'],2)?></td>
								</tr>
								<tr>
									<td>Over Time Rate</td>
									<td>: <?=number_format($r['otrate'],2)?></td>
									<td>Work Days</td>
									<td>: <?=number_format($r['ot'],2)?></td>
								</tr>
								<tr>
									<td>Shop Rate</td>
									<td>: <?=number_format($r['shoprate'],2)?></td>
									<td>Shop Days</td>
									<td>: <?=number_format($r['shopdays'],2)?></td>
								</tr>
								<tr><td colspan="4" class="dashed"></td></tr>
								<tr>
									<td colspan="2" class="uk-text-bold">Earnings</td>
									<td colspan="2" class="uk-text-bold">Deductions</td>
								</tr>
								<tr>
									<td>Work Pay</td>
									<td>: <?=number_format($r['wp'],2)?></td>
									<td>With Holding Tax</td>
									<td>: <?=number_format($r['tax'],2)?></td>
								</tr>
								<tr>
									<td>Over Time Pay</td>
									<td>: <?=number_format($r['otp'],2)?></td>
									<td>SSS</td>
									<td>: <?=number_format($r['sss'],2)?></td>
								</tr>
								<tr>
									<td>Shop Pay</td>
									<td>: <?=number_format($r['sp'],2)?></td>
									<td>PAG-IBIG</td>
									<td>: <?=number_format($r['pagibig'],2)?></td>
								</tr>
								<tr>
									<td>Incentives</td>
									<td>: <?=number_format($r['incentives'],2)?></td>
									<td>PhilHealth</td>
									<td>: <?=number_format($r['ph'],2)?></td>
								</tr>
								<tr>
									<td>Bonus</td>
									<td>: <?=number_format($r['bonus'],2)?></td>
									<td>Groceries</td>
									<td>: <?=number_format($r['groceries'],2)?></td>
								</tr>
								<tr>
									<td colspan="2"></td>
									<td>Loans</td>
									<td>: <?=number_format($r['loans'],2)?></td>
								</tr>
								<tr>
									<td colspan="2"></td>
									<td>Savings</td>
									<td>: <?=number_format($r['savings'],2)?></td>
								</tr>
								<tr>
									<td colspan="2"></td>
									<td>Cash Advances</td>
									<td>: <?=number_format($r['advance'],2)?></td>
								</tr>
								<tr class="uk-text-bold">
									<td>Total Earnings</td>
									<td>: <?=number_format($r['gross'],2)?></td>
									<td>Total Deductions</td>
									<td>: (<?=number_format($r['deducts'],2)?>)</td>
								</tr>
								<tr><td colspan="4" class="dashed"></td></tr>
								<tr class="uk-text-bold">
									<td>Date</td>
									<td>: <?=date('M. d, Y')?></td>
									<td>Total Pay</td>
									<td>: <?=number_format(($r['gross']-$r['deducts']),2)?></td>
								</tr>
								<tr><td colspan="4" style="height: 10px"></td></tr>
								<tr class="uk-text-bold">
									<td colspan="2" class="uk-text-bold">Noted By: __________</td>
									<td colspan="2" class="uk-text-bold">Received By: __________</td>
								</tr>
								<tr><td colspan="4" class="uk-text-bold uk-text-right"><?php if($i >= 2) {echo "Employee's Copy";} ?></td></tr>
							</table>
						</td>
						<?php } ?>
					</tr>
				</table>
			</div>
		<?php endforeach ?>
	</div>

	<script type="text/javascript">
		$(document).ready(function(){
			var printContents = $("#printDiv").html();
			var originalContents = document.body.innerHTML;
			document.body.innerHTML = printContents;
			window.print();
			document.body.innerHTML = originalContents;
			window.close();
		});
	</script>
</body>
</html>