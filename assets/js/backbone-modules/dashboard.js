var Dashboard = {
	'routes' : {
		'servers' : 'Servers',
		'servers/server/:server_id' : 'Servers',
		'servers/grid/:grid_name' : 'Servers',

		'metapackages' : 'Metapackages',

		'workers' : 'Workers',
		'workers/running' : 'Workers',
		'workers/idle' : 'Workers',
		'workers/package' : 'Workers',
		'workers/worker/:worker_id' : 'Workers',

		'processes' : 'Processes',

		'errors' : 'Errors',

		'*default' : 'Default'
	}	
};

Dashboard.Servers = function (filter_type, filter_id) {
	RouteHelper.init();

	if (fn.empty(filter_type)) {
		filter_type = 'all';
	}	

	if (fn.empty(filter_id)) {
		filter_id = 0;
	}

	var base_content = TemplateHelper.renderTemplate('servers');

	RouteHelper.appendContent(base_content);

	if (filter_id == 0) {
		Models.Servers.getMasters({}, function (data) {
			var content = TemplateHelper.renderTemplate('servers_masters_table', data.data);

			RouteHelper.appendContent(content, '#list_masters');
		});

		Models.Servers.getInstances({
			'instance_id' : 6950
		}, function (data) {
			console.log('instances data: ', data);
		});

		/*
		Models.Servers.getServersSummary({}, function (data) {
			data.title = 'Overall Summary';

			var content = TemplateHelper.renderTemplate('servers_summary', data);

			RouteHelper.prependContent(content);
		});

		Models.Servers.getServersByHealth({}, function (data) {
			var content = TemplateHelper.renderTemplate('servers_by_health', data);

			RouteHelper.appendContent(content, '#table_holder');
		});	

		Models.Servers.getServersByWorkers({}, function (data) {
			var content = TemplateHelper.renderTemplate('servers_by_workers', data);

			RouteHelper.appendContent(content, '#table_holder');
		});
		*/
	}
};

Dashboard.Metapackages = function () {
	RouteHelper.changeContent('Metapackages page');
};

Dashboard.Workers = function (filter_type) {
	RouteHelper.changeContent('Workers page');
};

Dashboard.Processes = function () {
	RouteHelper.changeContent('Processes page');
};

Dashboard.Errors = function () {
	RouteHelper.changeContent('Errors page');
};

Dashboard.Default = function () {
	RouteHelper.changeContent('Whoops, no page here.');	
};

RouteHelper.addRoute('dashboard', Dashboard);
RouteHelper.loadRoute('dashboard');
