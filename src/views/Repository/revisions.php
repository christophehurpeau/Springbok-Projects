<?php new AjaxContentView(_t('Revisions')) ?>
<?php $current='repository' ?>
{include ../Project/_viewMenu.php}
	<?php HBreadcrumbs::add(_t('Repository'),array($project->repositoryLink())); ?>
	
	
	{if $revisions->getTotalResults()===0}
	<div class="mGray">{t 'No revisions.'}</div>
	{else}
	<? $pager=HPagination::simple($revisions) ?>
	
	<table>
		<tr><th style="width:40px">#</th><th colspan="2"></th><th>{tC 'Date'}</th><th>{t 'Author'}</th><th>{t 'Comment'}</th></tr>
	{f $revisions->getResults() as $rev}
		<tr><td>{link $rev->revision,$project->repositoryLink('revision',$rev->revision)}</td><td></td><td></td><td><? HTime::compact($rev->committed,true) ?></td><td>{$rev->committer->name}</td><td>{$rev->comment}</td></tr>
	{/f}
	</table>
	
	<? $pager ?>
	{/if}
</div>