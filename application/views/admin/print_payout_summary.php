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
<body>
	<div id="printDiv">
		<div style="font-family: monospace;" class="uk-padding-small">
			<p class="uk-text-center uk-text-bold">
				PAYMENT SUMMARY<br>
				C-BROS CONSTRUCTION AND SAM'S MOUNTAIN VIEW RIDGE RESORT AND HOTEL<br>
				<?=date('F, Y', strtotime($date.'-01'))?>
			</p>
			<table class="uk-table uk-table-small uk-margin-remove uk-table-divider border" style="font-size: 12px;">
				<tr class="uk-text-uppercase uk-text-bold">
					<td>Name</td>
					<td>Group</td>
					<td>Designation</td>
					<td>TOTAL GROSS SALARY</td>
					<td>TOTAL DEDUCTIONS</td>
					<td>WORK DAYS</td>
					<td>Over Time</td>
					<td>SHOP DAYS</td>
					<td>NET SALARY</td>
				</tr>
				<?php 
				$g = 0;
				$d = 0;
				$n = 0;
				foreach ($rec as $r): 
					$t = $r['gross'] - $r['deducts'];
					$n += $t;
					$g += $r['gross'];
					$d += $r['deducts'];
				?>
				<tr>
					<td><?=$r['name']?></td>
					<td><?=$r['group']?></td>
					<td><?=$r['desig']?></td>
					<td align="right"><?=number_format($r['gross'])?></td>
					<td align="right"><?=number_format($r['deducts'])?></td>
					<td align="right"><?=number_format($r['wd'])?></td>
					<td align="right"><?=number_format($r['ot'])?></td>
					<td align="right"><?=number_format($r['sd'])?></td>
					<td align="right"><?=number_format($t)?></td>
				</tr>	
				<?php endforeach ?>
				<tr class="uk-text-uppercase uk-text-bold">
					<td colspan="3">Total</td>
					<td align="right"><?=number_format($g)?></td>
					<td align="right"><?=number_format($d)?></td>
					<td colspan="4" align="right"><?=number_format($n)?></td>
				</tr>
			</table>
		</div>
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