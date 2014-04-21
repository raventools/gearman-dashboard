
	<div class="col-md-9">
		<h4>{{title}}{{#unless title}}Arrays{{/unless}}</h4>

		<table id="arrays_all" class="table table-bordered">
			<thead>
				<th>Array Name</th>
				<th>ID</th>
				<th>Master ID</th>
				<th>Scaling</th>
				<th>Current Instances</th>
				<th>Min / Max Instances</th>
			</thead>

			{{#each servers}}
				<tr class="server-detail{{#if health_bad}} danger{{/if}}{{#if health_okay}} warning{{/if}}{{#if health_good}} success{{/if}}">
					<td class="server-attribute">
						{{#unless record_view}}<a href="/#arrays/{{array_id}}">{{/unless}}
							{{name}}
						{{#unless record_view}}</a>{{/unless}}
						{{#unless name}} wat {{/unless}}
					</td>
					<td class="server-attribute">
						{{array_id}}
						{{#unless array_id}} - {{/unless}}
					</td>
					<td class="server-attribute">
						{{master_id}}
						{{#unless master_id}} - {{/unless}}
					</td>
					<td class="server-attribute">
						{{scaling}}
						{{#unless scaling}} - {{/unless}}
					</td>
					<td class="server-attribute">
						{{cur_instances}}
						{{#unless cur_instances}} - {{/unless}}
					</td>
					<td class="server-attribute">
						{{#if min_instances}}
							{{#if max_instances}}
								{{min_instances}} / {{max_instances}}
							{{/if}}
						{{/if}}
						
						{{#unless min_instances}} - {{/unless}}
					</td>
				</tr>
			{{/each}}
		</table>
	</div>
