<?php new AjaxContentView(_t('Repository:').' '.$project->name) ?>
{include ../Project/_viewMenu.php}
	<p class="italic message error">{t 'No repository'}</p>
	{if $project->hasAccess('ManageRepository')}
	<div class="center">{iconLink 'add',_t('Add repository'),$project->link('repository_add','')}</div>
	{/if}
</div>