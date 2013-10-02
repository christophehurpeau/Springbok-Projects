<?php new MailView() ?>
<h1 style="font-size:14pt">Activation de votre compte {=CSettings::get('applicationTitle')}</h1>

Paramètres de connexion de votre compte :
<ul>
	<li>Adresse email : {$user->email}</li>
	<li>Mot de passe : {$user->pwd_confirm}</li>
</ul>

<p>Vous pouvez maintenant vous connecter sur {link '/',array('entry'=>'index','fullUrl'=>true)}</p>