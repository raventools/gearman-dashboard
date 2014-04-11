
	<div class="col-md-9">
		<h4>Servers</h4>

		<table id="workers_servers" class="table table-bordered">
			<thead>
				<th>Server Name</th>
				<th>Metapackages</th>
				<th>Public IP</th>
				<th>Worker Count</th>
			</thead>

			{{#each servers}}
				<tr class="server-detail{{#if health_bad}} danger{{/if}}{{#if health_okay}} warning{{/if}}{{#if health_good}} success{{/if}}">
					<td class="server-attribute">
						{{name}}
						{{#unless name}} wat {{/unless}}
					</td>
					<td class="server-attribute">
						{{metapackages}}
						{{#unless metapackages}} - {{/unless}}
					</td>
					<td class="server-attribute">
						{{public_ip}}
						{{#unless public_ip}} - {{/unless}}
					</td>
					<td class="server-attribute">
						{{worker_count}}
						{{#unless worker_count}} - {{/unless}}
					</td>
				</tr>
			{{/each}}
		</table>
	</div>
