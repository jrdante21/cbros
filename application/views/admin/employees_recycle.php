<div class="uk-padding-small uk-box-shadow-small uk-background-default">
	<div class="uk-flex uk-flex-bottom uk-margin">
		<div class="uk-margin-small-right">
			<label>Search:</label>
			<input type="text" name="searchInput" class="uk-input uk-form-small" placeholder="Search by ID or Name of Employee">
		</div>
		<div class="uk-margin-small-right">
			<button class="uk-button uk-button-primary uk-button-small uk-margin-small-right" id="sortBtn">Go</button>
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
	var page = 0;
	var sorts = {sort:1, order:'asc', group:0, stat:'FALSE'};

	$(document).ready(function(){
		get_employees();

		$("#sortBtn").click(function(){
			sorts.search = $("[name=searchInput]").val();
			get_employees();
		});
	});
	
	function paginate (n) {
		page = n;
		get_employees();
	}

	function get_employees () {
		$("#tbody").html(`<tr><td colspan="4" align="center"><i uk-spinner></i></td></tr>`);
		$.post({
			url: "<?=site_url('CBROS/ajax_requests/get_employees/')?>"+page,
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
								<a href="`+baseUrl+`profile/`+v.eID+`" class="uk-button uk-button-primary uk-button-small">View</a>
								&nbsp;
								<button class="uk-button uk-button-primary uk-button-small" onclick="restore_employee(`+v.eID+`)">Restore</button>
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

	function restore_employee (id) {
		var data = {emply:id, stat:'TRUE'};
		$.post({
			url: "<?=site_url('CBROS/ajax_requests/restore_employee/')?>",
			data: data,
			success: function (res) {
				get_employees();
				UIkit.notification({  message: 'Employee restore successfully!', status: 'success' });
			}
		});
	}

</script>