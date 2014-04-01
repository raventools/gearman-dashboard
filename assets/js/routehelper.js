var Routes = {},
	RouteHelper = {};

RouteHelper.addRoute = function (name, route_definition) {
	return Routes[name] = Backbone.Router.extend(route);	
};
