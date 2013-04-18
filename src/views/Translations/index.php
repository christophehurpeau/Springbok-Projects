<?php new AjaxContentView(_t('Translations')) ?>
{include _viewMenu}

<div>
	<ul>
{f $langs as $lang}
	<li>{$lang} : {link 'project',$project->translationLink('project','/'.$lang)}
		 - {*link 'project singular/plural',['/dev/:controller(/:action/*)?','langs','sp','/'.$lang]}
		 - {link 'Models',['/dev/:controller(/:action/*)?','langs','models','/'.$lang]}
		 - {link 'js',['/dev/:controller(/:action/*)?','langs','js','/'.$lang]}
		 - {link 'Plugins',['/dev/:controller(/:action/*)?','langs','plugins','/'.$lang]*}</li>
{/f}
	</ul>
</div>
