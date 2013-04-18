<?php new AjaxContentView(_t('Repository:').' '.$project->name) ?>

<?php $current='repository' ?>
{include _viewMenu.php}

	<h2><? _tF('Repository','New') ?></h2>
	
	{if!e $error}
	<p class="error message">{$error}</p>
	{/if}
	
	<?php $form=HForm::create('Repository',array('class'=>'mediumLabel'));
	echo $form->select('type',Repository::typesList())
		.$form->input('path')
	;
	echo $form->end();
	?>

</div>