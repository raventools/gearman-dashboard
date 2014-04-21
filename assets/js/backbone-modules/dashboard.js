var Dashboard = {
	'routes' : {
		'dashboard' : 'Dashboard',

		'servers' : 'Servers',
		'servers/:master_id' : 'Servers',

		'arrays' : 'Arrays',
		'arrays/:array_id' : 'Arrays',

		'metapackages' : 'Metapackages',

		'workers' : 'Workers',
		
		'processes' : 'Processes',

		'errors' : 'Errors',

		// default route for handling "404s"
		'*default' : 'Default'
	}	
};

/**
 * Dashboard route - a combination of data from other views
 **/

Dashboard.Dashboard = function () {
	RouteHelper.init('dashboard');

	RouteHelper.addView('servers');
	RouteHelper.addView('arrays');

	Dashboard.Servers.defaultView();
	Dashboard.Arrays.defaultView();
};

/**
 * Servers route
 **/

Dashboard.Servers = function (master_id) {
	if (fn.empty(master_id)) {
		master_id = 0;
	}

	if (master_id == 0) {
		RouteHelper.init('servers');

		Dashboard.Servers.defaultView();		
	}
	else {
		RouteHelper.init('server');

		Models.Servers.getMaster({
			'master_id' : master_id
		}, function (data) {
			data.title = 'Master Details'; 

			var content = TemplateHelper.renderTemplate('servers_masters_table', data);

			RouteHelper.appendContent(content, '#list_details');
		});

		Models.Arrays.getMasterArrays({
			'master_id' : master_id
		}, function (data) {
			data.title = 'Arrays in Master';

			var content = TemplateHelper.renderTemplate('arrays_all_table', data);

			RouteHelper.appendContent(content, '#list_arrays');
		});
	}
};

Dashboard.Servers.defaultView = function () {
	Models.Servers.getMasters({}, function (data) {
		var content = TemplateHelper.renderTemplate('servers_masters_table', data);

		RouteHelper.appendContent(content, '#list_masters');
	});

	Models.Servers.getInstances({}, function (data) {
		var content = TemplateHelper.renderTemplate('servers_instances_table', data);

		RouteHelper.appendContent(content, '#list_instances');
	});
};

/**
 * Arrays route
 **/

Dashboard.Arrays = function (array_id) {
	RouteHelper.init('arrays');

	if (fn.empty(array_id)) {
		array_id = 0;
	}

	if (array_id == 0) {
		Dashboard.Arrays.defaultView();
	}
	else {
		RouteHelper.init('array');

		Models.Arrays.getArray({
			'array_id' : array_id
		}, function (data) {
			data.title = 'Array Details';

			var content = TemplateHelper.renderTemplate('arrays_all_table', data);

			RouteHelper.appendContent(content, '#list_details');
		});

		Models.Instances.getArrayInstances({
			'array_id' : array_id
		}, function (data) {
			data.title = 'Instances in Array';

			var content = TemplateHelper.renderTemplate('arrays_all_table', data);

			RouteHelper.appendContent(content, '#list_instances');
		});
	}
};

Dashboard.Arrays.defaultView = function () {
	Models.Arrays.getArrays({}, function (data) {
		var content = TemplateHelper.renderTemplate('arrays_all_table', data);

		RouteHelper.appendContent(content, '#list_arrays');
	});
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
	RouteHelper.init('processes');

	Models.Processes.getProcesses({}, function (data) {
		var content = TemplateHelper.renderTemplate('processes_all_table', data.data);

		RouteHelper.appendContent(content, '#list_processes_all');
	});
};

Dashboard.Errors = function () {
	RouteHelper.init('errors');

	Models.Errors.getErrors({}, function (data) {
		var content = TemplateHelper.renderTemplate('errors_all_table', data.data);

		RouteHelper.appendContent(content, '#list_errors_all');
	});
};

Dashboard.Default = function (path) {
	if (_.isNull(path)) {
		RouteHelper.navigate(RouteHelper.default_route);		

		return false;
	}

	RouteHelper.changeContent('Whoops, no page here.');	
};

RouteHelper.addRoute('dashboard', Dashboard);
RouteHelper.loadRoute('dashboard');
