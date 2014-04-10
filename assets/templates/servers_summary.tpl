<h3>{{title}}</h3>

<div class="row">
	{{#each servers}}
		<div class="col-md-4">
			<span class="server-name">{{name}}</span>

			{{#each stats}}
				<span class="server-{{stat.name}}">{{stats.health}}</span>{{stat.name}}
			{{/each}}
		</div>	
	{{/each}}
</div>
