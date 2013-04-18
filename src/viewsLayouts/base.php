<? HHtml::doctype() ?>
<html>
	<head>
		<? HHtml::metaCharset() ?>
		<?php
			HHead::title($layout_title.(isset($project)?' - '.$project->name:'').' | '.CSettings::get('applicationTitle'));
			HHead::linkCssAndJs();
			HHead::linkAddJs('/syntaxhighlighter.min');
			HHead::jsI18n();
			HHead::display();
		?>
	</head>
	<body>
		{=$layout_content}
	</body>
</html>