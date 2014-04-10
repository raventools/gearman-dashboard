
	<div class="col-md-9">
		<h4>Packages</h4>

		<table id="metapackages_packages" class="table table-bordered">
			<thead>
				<th>Package Name</th>
				<th>Queued</th>
				<th>Active</th>
				<th>Worker Count</th>
			</thead>

			{{#each packages}}
				<tr class="package-detail{{#if health_bad}} danger{{/if}}{{#if health_okay}} warning{{/if}}{{#if health_good}} success{{/if}}">
					<td class="package-attribute">
						{{name}}
						{{#unless name}} wat {{/unless}}
					</td>
					<td class="package-attribute">
						{{queued}}
					</td>
					<td class="package-attribute">
						{{active}}
					</td>
					<td class="package-attribute">
						{{worker_count}}
						{{#unless worker_count}} 0 {{/unless}}
					</td>
				</tr>
			{{/each}}
		</table>
	</div>
