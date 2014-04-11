
	<div class="col-md-9">
		<h4>Processes</h4>

		<table id="processes_all" class="table table-bordered">
			<thead>
				<th>Process Description</th>
				<th>PID</th>
				<th>Server ID</th>
				<th>Status</th>
				<th>Date</th>
			</thead>

			{{#each processes}}
				<tr class="process-detail{{#if health_bad}} danger{{/if}}{{#if health_okay}} warning{{/if}}{{#if health_good}} success{{/if}}">
					<td class="process-attribute">
						{{description}}
						{{#unless description}} wat {{/unless}}
					</td>
					<td class="process-attribute">
						{{pid}}
						{{#unless pid}} - {{/unless}}
					</td>
					<td class="process-attribute">
						{{server_id}}
						{{#unless server_id}} - {{/unless}}
					</td>
					<td class="process-attribute">
						{{status}}
						{{#unless status}} - {{/unless}}
					</td>
					<td class="process-attribute">
						{{ts}}
						{{#unless ts}} - {{/unless}}
					</td>
				</tr>
			{{/each}}
		</table>
	</div>
