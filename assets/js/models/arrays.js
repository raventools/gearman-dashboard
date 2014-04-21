Models.Arrays = {
	'endpoint' : Endpoints.dummy_endpoint,
	'api_endpoint' : Endpoints.endpoint,

	'getArrays' : function (options, callback) {
		Request.get(this.api_endpoint + '/arrays', {}, function (response) {
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

	'getMasterArrays' : function (options, callback) {
		if (fn.empty(options) || fn.empty(options.master_id)) {
			throw ErrorHelper.getText('masters', 'missing_id');
		}

		Request.get(this.api_endpoint + '/arrays/' + options.master_id, {}, function (response) {
			var formatted_data = {
				'servers' : response.data
			};

			_.each(formatted_data.servers, function (server) {
				server.master_id = options.master_id;
			});

			if (_.isFunction(callback)) {
				callback(formatted_data);
			}
		}, 'json');		
	},

	'getArray' : function (options, callback) {
		if (fn.empty(options) || fn.empty(options.array_id)) {
			throw ErrorHelper.getText('arrays', 'missing_id');
		}

		Request.get(this.api_endpoint + '/arrays/' + options.array_id, {}, function (response) {
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
	}
};
