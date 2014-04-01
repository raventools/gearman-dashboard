var Routes = {},
	RouteHelper = {};

RouteHelper.addRoute = function (name, route_definition) {
	return Routes[name] = Backbone.Router.extend(route_definition);	
};

RouteHelper.loadRoute = function (name) {
	new Routes[name]; // crazy, i know

	Backbone.history.start();
};

RouteHelper.changeContent = function (content) {
	if (!_.isFunction($)) {
		return false;
	}

	$('#backbone_container').html(content);
};

RouteHelper.appendContent = function (content) {
	$('#backbone_container').append(content);
};

RouteHelper.prependContent = function (content) {
	$('#backbone_container').prepend(content);
};
