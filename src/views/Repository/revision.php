<?php new AjaxContentView($title=_t('Revision').' #'.$rev->revision) ?>
<?php
HBreadcrumbs::set(array(
	_t('Projects')=>'/project',
	$project->name=>array($project->link()),
	_t('Repository')=>array($project->repositoryLink()),
	_t('Revisions')=>array($project->repositoryLink('revisions')),
));
?>

<h1>{$title} - {$project->name}</h1>

<div class="content"><? HTime::nice($rev->committed) ?> {t 'by'} <b>{=$rev->committer()}</b></div>

<div class="content mt10">
	{$rev->comment}
</div>

{* TODO : ajouter parents/children if exists *}

<?php /* <% if @changeset.issues.visible.any? %>
<h3><%= l(:label_related_issues) %></h3>
<ul>
<% @changeset.issues.visible.each do |issue| %>
  <li><%= link_to_issue issue %></li>
<% end %>
</ul>
<% end %>  */ ?>

<h5 class="mt10">{t 'Files'}</h5>
<?php $tree=array();
foreach($rev->changes as $change){
	$p=&$tree;
	$dirs=explode('/',$change->path);
	$path='';
	foreach($dirs as $dir){
		if(empty($dir)) continue;
		$path==='' ? $path=$dir : $path.='/'.$dir;
		if(!isset($p['s'])) $p['s']=array();
		$p=&$p['s'];
		if(!isset($p[$path])) $p[$path]=array();
		$p=&$p[$path];
	}
	$p['c']=$change;
}
?>

{if!e $tree}
<?php $tree=$tree['s']; ?>
{recursiveFunction $tree use($project,$rev)}
<ul class="icons">
	{f $tree as $file=>$subtree}
		<li>
		<?php $icon='changed'; $name=basename($file); ?>
		{if!e $subtree['s']}{icon folder}
			{link $name,$project->repositoryLink('view',$file)}
			<?php $callback($callback,$subtree['s']) ?>
		{elseif!e $subtree['c']}<?php $c=$subtree['c'] ?><span class="icon file-{$c->type}"></span>
			{if $c->type==='D'}{$name}{else}{link $name,$project->repositoryLink('file',$file,'rev='.$rev->revision)}
				{if $c->type==='M'} ({link 'diff',$project->repositoryLink('file_diff',$file,'rev='.$rev->revision)}){/if}
			{/if}
		{/if}
		</li>
	{/f}
</ul>
{/recursiveFunction}
{/if}
