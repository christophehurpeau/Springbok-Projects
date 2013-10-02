<?php new AjaxContentView(_tF('User','New')) ?>

<?php HBreadcrumbs::set(array(_t('Users')=>'/adminUsers')) ?>

<?php $form=HForm::create('User') ?>
<div class="floatL wp50">
	{=$form->fieldsetStart(_t('Informations'))}
	{=$form->input('first_name')}
	{=$form->input('last_name')}
	{=$form->input('email')}
	{=$form->checkbox('status',_t('Administrator'))}
	{=$form->fieldsetStop()}
	<br/>
	{=$form->fieldsetStart(_t('Authentification'))}
	{=$form->input('pwd',array('type'=>'password','label'=>_t('Password')))}
	{=$form->input('pwd_confirm',array('type'=>'password','label'=>_t('Confirm password')))}
	{=$form->fieldsetStop()}
</div>

<br class="clear"/>

<div class="center">
	{=$form->checkbox('send_email',_t('Send account information to the user'))}
	{=$form->submit(_t('Create'))}
</div>
{=$form->end(false)}
