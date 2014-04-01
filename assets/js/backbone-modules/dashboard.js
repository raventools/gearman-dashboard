var Dashboard = {
	'routes' : {
		'servers' : 'servers',
		'servers/server/:server_id' : 'servers',
		'servers/grid/:grid_name' : 'servers',

		'metapackages' : 'metapackages',

		'workers' : 'workers',
		'workers/running' : 'workers',
		'workers/idle' : 'workers',
		'workers/package' : 'workers',
		'workers/worker/:worker_id' : 'workers',

		'processes' : 'processes',

		'errors' : 'errors',

		'*default' : 'default'
	}	
};

Dashboard.servers = function (filter_type, filter_id) {
	if (fn.empty(filter_type)) {
		filter_type = 'all';
	}	

	if (fn.empty(filter_id)) {
		filter_id = 0;
	}

	RouteHelper.changeContent('Servers page');
};

Dashboard.metapackages = function () {
	RouteHelper.changeContent('Metapackages page');
};

Dashboard.workers = function (filter_type) {
	RouteHelper.changeContent('Workers page');
};

Dashboard.processes = function () {
	RouteHelper.changeContent('Processes page');
};

Dashboard.errors = function () {
	RouteHelper.changeContent('Errors page');
};

Dashboard.default = function () {
	RouteHelper.changeContent('Whoops, no page here.');	
};

RouteHelper.addRoute('dashboard', Dashboard);
RouteHelper.loadRoute('dashboard');
