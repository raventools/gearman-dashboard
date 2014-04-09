var Routes = {},
	RouteHelper = {};

RouteHelper.container = $('#backbone_container');

/* perform initializing actions, such as clearing the content area */
RouteHelper.init = function () {
	(RouteHelper.container).html('<div class="loader-animation"><img src="/assets/img/loading.gif" /></div>');
};

RouteHelper.clearLoader = function () {
	$('div.loader-animation').remove();
};

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

	RouteHelper.clearLoader();

	(RouteHelper.container).html(content);
};

RouteHelper.appendContent = function (content, sub_container) {
	RouteHelper.clearLoader();

	if (!fn.empty(sub_container)) {
		$(sub_container, RouteHelper.container).append(content);
	}	
	else {
		(RouteHelper.container).append(content);	
	}
};

RouteHelper.prependContent = function (content) {
	RouteHelper.clearLoader();

	(RouteHelper.container).prepend(content);
};
