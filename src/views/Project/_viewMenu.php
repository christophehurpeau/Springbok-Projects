<?php HBreadcrumbs::set(array( _t('Projects')=>'/project' , $project->name=>array($project->link()) ),$v->get('layout_title')); ?>

<h1>{$project->name}</h1>
<?php if(!isset($current)) $current=null; ?>
<? HMenu::top(array(
	_t('Overview')=>array($project->link(),'current'=>$current==='overview'?1:0),
	_t('Activity')=>array($project->link('activity',''),'visible'=>$project->hasAccess('ProjectActivity'),'current'=>$current==='activity'?1:0),
	//_t('Issues')=>array($project->link('issues',''),'visible'=>$project->hasAccess('IssueView'),'current'=>($current==='issues'?1:0)),
	_t('Repository')=>array($project->repositoryLink(),'visible'=>$project->hasAccess('RepositoryView'),'current'=>($current==='repository'?1:0)),
	//_t('Wiki')=>array($project->wikiLink(),'visible'=>$project->hasAccess('WikiView'),'current'=>($current==='wiki'?1:0)),
	_t('Translations')=>array($project->link('translations'),'visible'=>$project->hasRepository() && $project->hasAccess('ProjectTranslations'),'current'=>($current==='translations'?1:0)),
	_t('Members')=>array($project->link('members',''),'visible'=>$project->hasAccess('ProjectSettings'),'current'=>($current==='members'?1:0)),
	//_t('Settings')=>array($project->link('settings',''),'visible'=>$project->isAdmin())
),array('startsWith'=>true)); ?>
<div class="content clearfix">
