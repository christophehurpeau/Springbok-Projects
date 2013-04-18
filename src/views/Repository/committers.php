<?php new AjaxContentView($lastPageName=_t('Committers')) ?>
<?php $current='repository' ?>
{include ../Project/_viewMenu.php}
	<?php HBreadcrumbs::add(_t('Repository'),array($project->repositoryLink())); ?>
	
	{ife $committers}
	<p class="mWarning">{t 'No committers yet'}</p>
	{else}
	<?php $form=HForm::create(null,array(),false,false) ?>
	<table style="width:auto">
		<tr><th>{t 'Committer'}</th><th>{tC 'User'}</th></tr>
		{f $committers as $committer}
		<tr><td>{$committer->name}</td><td>{=$form->select('committers['.$committer->id.']',$users,array('selected'=>$committer->user_id,'empty'=>''))}</td></tr>
		{/f}
	</table>
	{=$form->end()}
	{/if}
</div>