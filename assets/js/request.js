var Request = {};

Request.get = function () {
	$.get.apply(null, arguments);	
};

Request.post = function () {
	$.post.apply(null, arguments);	
};

Request.ajax = function () {
	$.ajax.apply(null, arguments);	
};
