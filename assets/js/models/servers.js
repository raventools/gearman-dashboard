Models.Servers = {
	'endpoint' : '/dashboard/api', // dummy endpoint

	'getServerList' : function (options, callback) {
		var filter_type = 'all',
			filter_id = 0;

		if (_.isObject(options) && !fn.empty(options.filter_type)) {
			filter_type = options.filter_type;
		}

		if (_.isObject(options) && !fn.empty(options.filter_id)) {
			filter_id = options.filter_id;
		}

		Request.get(this.endpoint, {
			'method' : 'getServerList'
		}, function (response) {
			if (_.isFunction(callback)) {
				callback(response);
			}
		}, 'json');
	}
};
