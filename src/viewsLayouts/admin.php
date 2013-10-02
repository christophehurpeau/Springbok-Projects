<?php new AjaxPageView($layout_title) ?>
<div class="col fixed left w200">
	{menuLeft
		_t('General')=>'/admin',
		_t('Projects')=>'/adminProjects',
		_t('Users')=>'/adminUsers',
		_t('Roles & Permissions')=>'/acl',
	}
</div>
<div class="col variable">
	<h1>{$layout_title}</h1>
	<?php HBreadcrumbs::display(_tC('Home'),$layout_title) ?>
	{=$layout_content}
</div>