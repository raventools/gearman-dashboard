var Dashboard = {
	'routes' : {
		'servers' : 'servers',
		'servers/server/:server_id' : 'servers',
		'servers/grid/:grid_name' : 'servers',

		'metapackage' : 'metapackage',

		'workers' : 'workers',
		'workers/worker/:worker_id' : 'workers',

		'processes' : 'processes',

		'errors' : 'errors'
	}	
};

Dashboard.servers = function (filter_type, filter_id) {
	if (fn.empty(filter_type)) {
		filter_type = 'all';
	}	

	if (fn.empty(filter_id)) {
		filter_id = 0;
	}
};

Dashboard.metapackage = function () {

};

RouteHelper.addRoute('dashboard', Dashboard);
