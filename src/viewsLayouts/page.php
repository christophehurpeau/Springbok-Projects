<?php new AjaxBaseView($layout_title) ?>
<header>
	<div id="logo"><?= CSettings::get('applicationTitle') ?></div>
	{includePlugin users/views/_connected}
	{menuTop 'startsWith':true
		_tC('Home'):false,
		_t('Projects'):'/project',
		_t('Administration'):array('/admin','visible'=>CSecure::isConnected() && CSecure::user()->isAdmin()),
	}
</header>
{=$layout_content}
<footer>Version du <b><? HHtml::enhanceDate() ?></b> | <? HHtml::powered() ?></footer>