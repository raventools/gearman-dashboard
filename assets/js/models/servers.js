Models.Servers = {
	'endpoint' : Endpoints.dummy_endpoint,
	'api_endpoint' : Endpoints.endpoint,

	'getMasters' : function (options, callback) {
		Request.get(this.api_endpoint + '/masters', {}, function (response) {
			var formatted_data = {
				'servers' : response.data
			};

			if (_.isFunction(callback)) {
				callback(formatted_data);
			}
		}, 'json');		
	},

	'getInstances' : function (options, callback) {
		Request.get(this.api_endpoint + '/instances', function (response) {
			var formatted_data = {
				'servers' : []
			};

			var master, instance, new_instance;

			for (master in response.data) {
				for (instance in (response.data)[master]) {
					new_instance = (response.data)[master][instance];
					new_instance.master_id = master;

					(formatted_data.servers).push(new_instance);
				}
			}

			if (_.isFunction(callback)) {
				callback(formatted_data);
			}
		}, 'json');		
	},

	'getMaster' : function (options, callback) {
		if (fn.empty(options) || fn.empty(options.master_id)) {
			throw ErrorHelper.getText('masters', 'missing_id');
		}

		Request.get(this.api_endpoint + '/masters/' + options.master_id, {}, function (response) {
			var formatted_data = {
				'servers' : [
					response.data
				]
			};

			formatted_data.servers[0].record_view = 1; // signify that this is an individual record

			if (_.isFunction(callback)) {
				callback(formatted_data);
			}
		}, 'json');		
	}
};
