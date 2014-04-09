
	<div class="col-md-6">
		<h4>Servers (by worker count)</h4>

		<table id="servers_by_workers" class="table table-bordered">
			<thead>
				<th>Server Name</th>
				<th>Health</th>
				<th>Expected Workers</th>
				<th>Actual Workers</th>
			</thead>

			{{#each servers}}
				<tr class="server-detail{{#if stats.health_bad}} danger{{/if}}{{#if stats.health_okay}} warning{{/if}}{{#if stats.health_good}} success{{/if}}">
					<td class="server-name">{{name}}</td>
					<td class="server-health">{{stats.health}}</td>
					<td class="server-workers">{{expected_workers}}</td>
					<td class="server-workers">{{workers}}</td>
				</tr>
			{{/each}}
		</table>
	</div>
