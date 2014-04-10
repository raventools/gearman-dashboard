
	<div class="col-md-6">
		<h4>{{title}}</h4>

		<table id="servers_by_health" class="table table-bordered">
			<thead>
				{{#each headings}}
					<th>{{.}}</th>
				{{/each}}
			</thead>

			{{#each servers}}
				<tr class="server-detail{{#if stats.health_bad}} danger{{/if}}{{#if stats.health_okay}} warning{{/if}}{{#if stats.health_good}} success{{/if}}">
					{{#each ../headings}}
						<td class="server-attribute">{{getDataValue value servers.attributes}}</td>
					{{/each}}
				</tr>
			{{/each}}
		</table>
	</div>
