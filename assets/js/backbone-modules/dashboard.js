var Dashboard = {
	'routes' : {
		'servers' : 'Servers',
		'servers/server/:server_id' : 'Servers',
		'servers/grid/:grid_name' : 'Servers',

		'metapackages' : 'Metapackages',

		'workers' : 'Workers',
		/*
		'workers/running' : 'Workers',
		'workers/idle' : 'Workers',
		'workers/package' : 'Workers',
		'workers/worker/:worker_id' : 'Workers',
		*/

		'processes' : 'Processes',

		'errors' : 'Errors',

		'*default' : 'Default'
	}	
};

Dashboard.Servers = function (filter_type, filter_id) {
	RouteHelper.init('servers');

	if (fn.empty(filter_type)) {
		filter_type = 'all';
	}	

	if (fn.empty(filter_id)) {
		filter_id = 0;
	}

	if (filter_id == 0) {
		Models.Servers.getMasters({}, function (data) {
			var content = TemplateHelper.renderTemplate('servers_masters_table', data.data);

			RouteHelper.appendContent(content, '#list_masters');
		});

		Models.Servers.getInstances({}, function (data) {
			var content = TemplateHelper.renderTemplate('servers_instances_table', data.data);

			RouteHelper.appendContent(content, '#list_instances');
		});
	}
};

Dashboard.Metapackages = function () {
	RouteHelper.init('metapackages');

	Models.Metapackages.getPackages({}, function (data) {
		var content = TemplateHelper.renderTemplate('metapackages_packages_table', data.data);

		RouteHelper.appendContent(content, '#list_packages');
	});
};

Dashboard.Workers = function (filter_type) {
	RouteHelper.init('workers');

	Models.Workers.getServers({}, function (data) {
		var content = TemplateHelper.renderTemplate('workers_servers_table', data.data);

		RouteHelper.appendContent(content, '#list_servers');
	});
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
