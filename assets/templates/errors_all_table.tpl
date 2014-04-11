
	<div class="col-md-9">
		<h4>Errors</h4>

		<table id="errors_all" class="table table-bordered">
			<thead>
				<th>Error Message</th>
				<th>Server Name</th>
				<th>Date</th>
			</thead>

			{{#each errors}}
				<tr class="error-detail{{#if health_bad}} danger{{/if}}{{#if health_okay}} warning{{/if}}{{#if health_good}} success{{/if}}">
					<td class="error-attribute">
						{{message}}
						{{#unless message}} wat {{/unless}}
					</td>
					<td class="error-attribute">
						{{server_name}}
						{{#unless server_name}} - {{/unless}}
					</td>
					<td class="error-attribute">
						{{ts}}
						{{#unless ts}} - {{/unless}}
					</td>
				</tr>
			{{/each}}
		</table>
	</div>
