Models.Processes = {
	'endpoint' : Endpoints.dummy_endpoint,
	'api_endpoint' : Endpoints.endpoint,

	'getProcesses' : function (options, callback) {
		Request.get(this.endpoint, {
			'method' : 'getProcessesAll'
		}, function (response) {
			response.data.processes = response.data;
			
			if (_.isFunction(callback)) {
				callback(response);
			}
		}, 'json');		
	}
};
