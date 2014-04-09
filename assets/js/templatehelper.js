var Templates = {},
	TemplateHelper = {};

TemplateHelper.loadTemplate = function (template_name) {
	var template_source = $('#' + template_name).html();

	if (template_source == '') {
		Templates[template_name] = '';

		return false;
	}

	// fix content from server-side templates rendered in script tags
	template_source = decodeURIComponent(template_source);
	template_source = template_source.replace(/\+/g, ' ');

	Templates[template_name] = template_source;

	return true;	
};

TemplateHelper.getTemplate = function (template_name) {
	if (fn.empty(Templates[template_name])) {
		TemplateHelper.loadTemplate(template_name);
	}

	return Templates[template_name];
};

TemplateHelper.renderTemplate = function (template_name, data) {
	var template_contents = TemplateHelper.getTemplate(template_name),
		template = Handlebars.compile(template_contents),
		content = template(data);

	return content;
};
