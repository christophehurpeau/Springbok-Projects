<?php new AjaxContentView(_t('Edit member')) ?>

<?php $current='members' ?>
{include _viewMenu.php}
	<?php HBreadcrumbs::add(_t('Members'),$project->link('members','')) ?>
	
	<h2>{t 'Member:'} {$member->user->name()}</h2>
	<?php $form=HForm::create(null) ?>
	{=$form->fieldsetStart(_t('Roles'))}
	{=$form->multiple('roles',AclGroup::findListName(),array('style'=>'checkbox','label'=>false,'selected'=>$member->roles))}
	{=$form->end()}
</div>