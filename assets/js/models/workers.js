Models.Workers = {
	'endpoint' : Endpoints.dummy_endpoint,
	'api_endpoint' : Endpoints.endpoint,

	'getServers' : function (options, callback) {
		Request.get(this.endpoint, {
			'method' : 'getWorkersServers'
		}, function (response) {
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
	}
};
