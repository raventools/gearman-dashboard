Models.Errors = {
	'endpoint' : Endpoints.dummy_endpoint,
	'api_endpoint' : Endpoints.endpoint,

	'getErrors' : function (options, callback) {
		Request.get(this.endpoint, {
			'method' : 'getErrorsAll'
		}, function (response) {
			response.data.errors = response.data;
			
			if (_.isFunction(callback)) {
				callback(response);
			}
		}, 'json');		
	}
};
