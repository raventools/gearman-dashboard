Models.Errors = {
	'endpoint' : '/dashboard/api', // dummy endpoint
	'api_endpoint' : '/gearmandashboardapi',

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
