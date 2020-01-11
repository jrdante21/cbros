<div class="uk-padding-small uk-box-shadow-small uk-background-default">
	<div class="uk-flex uk-flex-bottom uk-margin">
		<div class="uk-margin-small-right">
			<label>Sort by:</label>
			<select class="uk-select uk-form-small" name="sortBySelect"></select>
		</div>
		<div class="uk-margin-small-right">
			<label>Group:</label>
			<select class="uk-select uk-form-small" name="groupSelect"></select>
		</div>
		<div class="uk-margin-small-right">
			<label>Order:</label>
			<select class="uk-select uk-form-small" name="orderSelect"></select>
		</div>
		<div class="uk-margin-small-right">
			<label>Search:</label>
			<input type="text" name="searchInput" class="uk-input uk-form-small" placeholder="Search by ID or Name of Employee">
		</div>
		<div class="uk-margin-small-right">
			<button class="uk-button uk-button-primary uk-button-small uk-margin-small-right" id="sortBtn">Go</button>
		</div>
		<div>
			<form action="<?=site_url('CBROS/print/group_payslip/')?>" method="POST" id="printPayslip">
				<input type="hidden" name="groupPP">
				<input type="hidden" name="sortPP">
				<input type="hidden" name="orderPP">
				<input type="hidden" name="paginatePP" value="0">
				<button class="uk-button uk-button-primary uk-button-small uk-margin-small-right">Print Payslip</button>
			</form>
		</div>
	</div>
	<table class="uk-table uk-table-small uk-text-small uk-margin-remove uk-table-divider border uk-table-hover">
		<thead class="uk-alert">
			<th>Name</th>
			<th>Address</th>
			<th>Group</th>
			<th class="uk-text-right">Actions</th>
		</thead>
		<tbody id="tbody"></tbody>
	</table>
</div>
<script type="text/javascript">
	var s =  get_sorts();
	var group = <?=json_encode($group)?>;

	var sortSel = {1:'First Name', 2:'Last Name', 3:'Employee ID'};
	var orderArray = {asc:'Ascending', desc:'Descending'};

	$(document).ready(function(){
		get_employees(0, s);
		setInputs();

		var sortBySelect = '';
		$.each(sortSel, function(k,v){
			if (k == s.sort) {
				sortBySelect += `<option value="`+k+`" selected="selected">`+v+`</option>`;
			} else {
				sortBySelect += `<option value="`+k+`">`+v+`</option>`;
			}
		});
		$("[name=sortBySelect]").html(sortBySelect);

		var groupSelect = '<option value="0">All</option>';
		$.each(group, function(gk, gv){
			if (gv.gID == s.group) {
				groupSelect += `<option value="`+gv.gID+`" selected="selected">`+gv.gName+`</option>`;
			} else {
				groupSelect += `<option value="`+gv.gID+`">`+gv.gName+`</option>`;
			}
		});
		$("[name=groupSelect]").html(groupSelect);

		var orderSelect = '';
		$.each(orderArray, function(og, vg){
			if (og == s.order) {
				orderSelect += `<option value="`+og+`" selected="selected">`+vg+`</option>`;
			} else {
				orderSelect += `<option value="`+og+`">`+vg+`</option>`;
			}
		});
		$("[name=orderSelect]").html(orderSelect);

		$("#sortBtn").click(function(){
			var data = {};
			data.sort = $("[name=sortBySelect]").val();
			data.group = $("[name=groupSelect]").val();
			data.order = $("[name=orderSelect]").val();
			set_sorts(data);

			data.search = $("[name=searchInput]").val();
			get_employees(0,data);
			setInputs();
		});

		$("#printPayslip").submit(function(){
			window.open('', 'printPayslip', 'width=1000,height=660,scrollbars=yes,menubar=no,status=yes,resizable=yes,screenx=0,screeny=0'); 
			this.target = 'printPayslip';
		});

	});

	function paginate (n) {
		var st = get_sorts();
		get_employees(n, st);
		$("[name=paginatePP]").val(n);
	}

	function setInputs () {
		var data = get_sorts();
		$("[name=groupPP]").val(data.group);
		$("[name=sortPP]").val(data.sort);
		$("[name=orderPP]").val(data.order);
	}

	function get_employees (url, sorts) {
		sorts.stat = 'TRUE';
		$("#tbody").html(`<tr><td colspan="4" align="center"><i uk-spinner></i></td></tr>`);
		$.post({
			url: "<?=site_url('CBROS/ajax_requests/get_employees/')?>"+url,
			data: sorts,
			success: function (res) {
				var r = JSON.parse(res);
				var td = '';
				$.each(r.data, function(k,v){
					var baseUrl = "<?=site_url('CBROS/employee/')?>";
					td += `
						<tr>
							<td>`+v.eID+`&nbsp;-&nbsp;`+v.name+`</td>
							<td>`+v.address+`</td>
							<td>`+v.gname+`</td>
							<td align="right">
								<a href="`+baseUrl+`profile/`+v.eID+`" class="uk-button uk-button-primary uk-button-small">Profile</a>
								<div class="uk-inline uk-text-left">
									<button class="uk-button uk-button-default uk-button-small">More</button>
									<div uk-dropdown="mode:click">
										<ul class="uk-nav uk-dropdown-nav">
											<li><a href="`+baseUrl+`records/`+v.eID+`">Records</a></li>
											<li><a href="`+baseUrl+`advances/`+v.eID+`">Advances</a></li>
											<li><a href="`+baseUrl+`savings/`+v.eID+`">Savings</a></li>
											<li><a href="`+baseUrl+`payslips/`+v.eID+`">Payslips</a></li>
										</ul>
									</div>
								</div>
							</td>
						</tr>
							`;
				});

				if (r.data.length <= 0) {
					td += `<tr><td colspan="4" align="center">No data found...</td></tr>`;
				}

				if (r.link != '') {
					td += `<tr><td colspan="4" align="center">`+r.link+`</td></tr>`;
				}
				
				$("#tbody").html(td);
			}
		});
	}

	function get_sorts () {
		var data = localStorage.getItem('sorts');
		if (!data) {
			data = {sort:1, order:'asc', group:0};
			return data;
		} else {
			return JSON.parse(data);
		}
	}

	function set_sorts (data) {
		localStorage.setItem('sorts', JSON.stringify(data));
	}
</script>
