var Routes = {},
	RouteHelper = {};

RouteHelper.container = $('#backbone_container');
RouteHelper.navigation = $('#dashboard_nav');

/* perform initializing actions, such as clearing the content area */
RouteHelper.init = function (route_name) {
	(RouteHelper.container).html('<div class="loader-animation"><img src="/assets/img/loading.gif" /></div>');

	if (!fn.empty(route_name)) {
		RouteHelper.highlightNav(route_name);

		var base_content = TemplateHelper.renderTemplate(route_name);

		RouteHelper.appendContent(base_content);
	}
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

RouteHelper.highlightNav = function (route_name) {
	var this_nav = $(RouteHelper.navigation),
		this_nav_item = this_nav.find('a.nav-' + route_name),
		other_nav_items = this_nav.find('a.nav-item').not(this_nav_item);

	other_nav_items.removeClass('active');
	this_nav_item.addClass('active');	
};
