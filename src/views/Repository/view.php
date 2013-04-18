<?php new AjaxContentView(_t('Repository')) ?>
{include _viewMenu.php}

	<?php $path=empty($path)?'':$path.'/'; ?>
	{if!e $entries}
	<table class="table">
		<thead><tr><th class="alignLeft">{t 'Name'}</th><th class="w1">{t 'Size'}</th></tr></thead>
		<tbody>
			{if!e $entries['folders']}
				{f $entries['folders'] as $name=>$folder}<tr><td>{link $name,$project->repositoryLink('view',$path.$name)}</td><td></td></tr>{/f}
			{/if}
			{if!e $entries['files']}
				{f $entries['files'] as $name=>$file}<tr><td>{link $name,$project->repositoryLink('file',$path.$name)}</td><td class="alignRight nowrap">{$file['size']} bytes</td></tr>{/f}
			{/if}
		</tbody>
	</table>
	{/if}
	
	{if!e $latestRevisions}
	<h5 class="sepTop">{t 'Latest revisions'}</h5>
	<table class="table smallinfo">
		<thead><tr><th class="alignLeft">#</th><th colspan="2"></th><th>{tC 'Date'}</th><th>{t 'Author'}</th><th>{t 'Comment'}</th></tr></thead>
		{f $latestRevisions as $rev}
		<tr><td>{link $rev['identifier'],$project->repositoryLink('revision',$rev['identifier'])}</td><td></td><td></td><td><? HTime::compactTime($rev['time'],true) ?></td><td>{$rev['authorName']}</td><td>{$rev['comment']}</td></tr>
		{/f}
	</table>
	
	<div class="alignRight">{link _t('All revisions'),$project->repositoryLink('revisions','')}</div>
	{/if}
</div>