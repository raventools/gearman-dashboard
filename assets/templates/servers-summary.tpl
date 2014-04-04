<h3>Overall Summary</h3>

<div class="row">
	{{#each servers}}
		<div class="col-md-4">
			<span class="server-name">{{name}}</span>
			<span class="server-health">{{stats.health}}</span>
		</div>	
	{{/each}}
</div>
