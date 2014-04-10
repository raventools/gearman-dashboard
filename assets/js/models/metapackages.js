Models.Metapackages = {
	'endpoint' : '/dashboard/api', // dummy endpoint
	'api_endpoint' : '/gearmandashboardapi',

	'getPackages' : function (options, callback) {
		Request.get(this.endpoint, {
			'method' : 'getPackages'
		}, function (response) {
			var formatted_data = [];

			_.each(response.data, function (value, key) {
				value['name'] = key;

				formatted_data.push(value);
			});

			response.data.packages = formatted_data;

			if (_.isFunction(callback)) {
				callback(response);
			}
		}, 'json');		
	}
};
