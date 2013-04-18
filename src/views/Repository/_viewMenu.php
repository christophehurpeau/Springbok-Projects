<?php $current='repository' ?>
{include ../Project/_viewMenu.php}

<div class="content alignRight">
	{if $project->isAdmin()}{link _t('Committers'),$project->repositoryLink('committers','')} | {/if}
	{link _t('Revisions'),$project->repositoryLink('revisions','')} | 
	Branch : {* <? HHtml::select($repository->branches(),array()) ?> *}
</div>

<?php 
if(empty($path)){
	HBreadcrumbs::setLast(_t('Repository'));
}else{
	HBreadcrumbs::add(_t('Repository'),array($project->repositoryLink()));
	$epath=explode('/',$path);
	$fp=$epath[0];unset($epath[0]);
	if(empty($epath)) HBreadcrumbs::setLast($fp);
	else{
		$links=array();
		$links[$fp]='\\'.HHtml::url($project->repositoryLink('view',$fp));
		foreach($epath as $p){
			$fp.='/'.$p;
			$links[$p]='\\'.HHtml::url($project->repositoryLink('view',$fp));
		}
		unset($links[$p]);
		foreach($links as $title=>$link) HBreadcrumbs::add($title,$link); //todo : 2 foreach ~
		HBreadcrumbs::setLast($p);
	}
}
?>