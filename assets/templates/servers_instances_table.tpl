
	<div class="col-md-9">
		<h4>Instances</h4>

		<table id="servers_instances" class="table table-bordered">
			<thead>
				<th>Server Name</th>
				<th>State</th>
				<th>Deployment ID</th>
				<th>Server Template ID</th>
			</thead>

			{{#each servers}}
				<tr class="server-detail{{#if health_bad}} danger{{/if}}{{#if health_okay}} warning{{/if}}{{#if health_good}} success{{/if}}">
					<td class="server-attribute">
						{{name}}
						{{#unless name}} wat {{/unless}}
					</td>
					<td class="server-attribute">
						{{state}}
						{{#unless state}} - {{/unless}}
					</td>
					<td class="server-attribute">
						{{deployment_id}}
						{{#unless deployment_id}} - {{/unless}}
					</td>
					<td class="server-attribute">
						{{server_template_id}}
						{{#unless server_template_id}} - {{/unless}}
					</td>
				</tr>
			{{/each}}
		</table>
	</div>
