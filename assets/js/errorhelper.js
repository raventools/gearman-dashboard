var Errors = {},
	ErrorHelper = {};

ErrorHelper.getText = function (namespace, error_type) {
	var error_message = 'An unknown or undocumented error occurred.',
		prefix = '[' + namespace.toUpperCase() + '] ';

	if (!Errors.hasOwnProperty(namespace)) {
		return error_message;
	}	

	if ((Errors[namespace]).hasOwnProperty(error_type)) {
		error_message = Errors[namespace][error_type];
	}

	return prefix + error_message;
};

Errors.masters = {
	'missing_id' : 'Master id is required and was not provided.'
};

Errors.arrays = {
	'missing_id' : 'Array id is required and was not provided.'
};
