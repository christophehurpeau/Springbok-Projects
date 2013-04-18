<?php new View(_t('Projects')); HMeta::canonical('/project') ?>

{if ACAcl::checkAccess('CreateProject')}
	<div class="alignRight small-size">{iconLink 'add',_t('New project'),'/project/add'}</div>
{/if}
{ife $projects}
	<p>{t 'No projects.'}</p>
{else}
	<ul class="nobullets spaced">
	{f $projects as $project}
		<li>{link $project->name,$project->link()}<br/>{$project->text}</li>
	{/f}
	</ul>
{/if}
