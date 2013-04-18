<?php new AjaxContentView(_t('New project')) ?>
<?php HBreadcrumbs::set(array(_t('Projects')=>'/project')); ?>

<h1>{t 'New project'}</h1>
<div>
<?php $form=HForm::create('Project',array('class'=>'mediumLabel'));
echo $form->input('name')
	.$form->textarea('text')
	.$form->input('website')
	.$form->yesOrNo('public',array('value'=>CSettings::get('Projects.publicByDefault')));
echo $form->end(_tC('Add'));
 ?>
</div>
