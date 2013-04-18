<?php new AjaxContentView($path.' - '._t('History')); ?>
{include _viewMenu.php}
	{include _viewMenuFile.php}
	<table class="table">
		<thead>
			<tr><th class="alignLeft">#</th><th colspan="2"></th><th>{tC 'Date'}</th><th>{t 'Author'}</th><th>{t 'Comment'}</th></tr>
		</thead>
		<tbody>
			{f $history as $rev}
			<tr><td>{link $rev['identifier'],$project->repositoryLink('file',$path,'rev='.$rev['identifier'])}&nbsp;({link 'diff',$project->repositoryLink('file_diff',$path,'rev='.$rev['identifier'])})</td><td></td><td></td><td><? HTime::compactTime($rev['time'],true) ?></td><td>{$rev['authorName']}</td><td><? nl2br(h($rev['comment'])) ?></td></tr>
			{/f}
		</tbody>
	</table>
</div>