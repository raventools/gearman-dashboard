Models.Servers = {
	'endpoint' : Endpoints.dummy_endpoint,
	'api_endpoint' : Endpoints.endpoint,

	'getMasters' : function (options, callback) {
		Request.get(this.api_endpoint + '/masters', {}, function (response) {
			var formatted_data = [];

			_.each(response.data, function (value, key) {
				value['name'] = key;

				formatted_data.push(value);
			});

			response.data.servers = formatted_data;

			if (_.isFunction(callback)) {
				callback(response);
			}
		}, 'json');		
	},

	'getInstances' : function (options, callback) {
		Request.get(this.api_endpoint + '/instances', function (response) {
			var formatted_data = [];

			_.each(response.data, function (value, key) {
				value['name'] = key;

				formatted_data.push(value);
			});

			response.data.servers = formatted_data;

			if (_.isFunction(callback)) {
				callback(response);
			}
		}, 'json');		
	},

	'getServersSummary' : function (options, callback) {
		var filter_type = 'all',
			filter_id = 0;

		if (_.isObject(options) && !fn.empty(options.filter_type)) {
			filter_type = options.filter_type;
		}

		if (_.isObject(options) && !fn.empty(options.filter_id)) {
			filter_id = options.filter_id;
		}

		Request.get(this.endpoint, {
			'method' : 'getServersSummary'
		}, function (response) {
			if (_.isFunction(callback)) {
				callback(response);
			}
		}, 'json');
	},

	'getServersByHealth' : function (options, callback) {
		var filter_type = 'all',
			filter_id = 0;

		if (_.isObject(options) && !fn.empty(options.filter_type)) {
			filter_type = options.filter_type;
		}

		if (_.isObject(options) && !fn.empty(options.filter_id)) {
			filter_id = options.filter_id;
		}

		Request.get(this.endpoint, {
			'method' : 'getServersByHealth'
		}, function (response) {
			if (_.isFunction(callback)) {
				callback(response);
			}
		}, 'json');
	},

	'getServersByWorkers' : function (options, callback) {
		var filter_type = 'all',
			filter_id = 0;

		if (_.isObject(options) && !fn.empty(options.filter_type)) {
			filter_type = options.filter_type;
		}

		if (_.isObject(options) && !fn.empty(options.filter_id)) {
			filter_id = options.filter_id;
		}

		Request.get(this.endpoint, {
			'method' : 'getServersByWorkers'
		}, function (response) {
			if (_.isFunction(callback)) {
				callback(response);
			}
		}, 'json');
	}
};
