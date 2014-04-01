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
	var content = $('<div class="row" />');

	if (fn.empty(filter_type)) {
		filter_type = 'all';
	}	

	if (fn.empty(filter_id)) {
		filter_id = 0;
	}

	Models.Servers.getServerList({}, function (data) {
		_.each(data.servers, function (server) {
			var new_listing = $('<div class="col-md-4" />');

			new_listing.append('<span class="server-name">' + server.name + '</span>');
			new_listing.append('<span class="server-health">' + server.stats.health + '</span>');

			content.append(new_listing);
		});

		RouteHelper.changeContent(content);
	});

	RouteHelper.changeContent('Loading...');
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
