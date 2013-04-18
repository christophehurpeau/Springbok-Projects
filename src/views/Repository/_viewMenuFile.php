<?php $routeAction=CRoute::getAction(); $queryParams=isset($_GET['rev'])?'rev='.$_GET['rev']:null; ?>
<nav class="top">
	<ul>
		{menuLink _t('View'),array('current'=>$routeAction==='file',$project->repositoryLink('file',$path,$queryParams))}
		{menuLink _t('History'),array('current'=>$routeAction==='file_history',$project->repositoryLink('file_history',$path,$queryParams))}
		{if isset($_GET['rev'])}{menuLink _t('Diff'),array('current'=>$routeAction==='file_diff',$project->repositoryLink('file_diff',$path,$queryParams))}{/if}
		<li>{link 'Download',$project->repositoryLink('file',$path,'download'.($queryParams===null?'':'&'.$queryParams)),array('target'=>'_blank')}</li>
	</ul>
</nav>
<div class="content clear">
	{if isset($rev)}{t 'Revision:'} {tC '#'}{$rev}{/if}
</div> 