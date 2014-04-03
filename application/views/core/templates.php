<?php foreach ($templates as $template_name => $template_contents): ?>
	<script id="<?php echo str_replace('-', '_', $template_name); ?>" type="text/x-handlebars-template">
		<?php echo $template_contents; ?>
	</script>
<?php endforeach; ?>
