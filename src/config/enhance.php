<?php return array(
	'base'=>array('i18n'),

	'includes'=>array(
		'tinymce'
	),
	
	'plugins'=>array(
		'contactForm'=>array('SpringbokCore','contactForm'),
		'searchable'=>array('SpringbokCore','searchable'),
	
		'users'=>array('SpringbokCore','users'),
		'acl'=>array('SpringbokCore','acl'),
	),
	
	'modelParents'=>array(
		'Searchable'=>array(/*0=>'Post',1=>'Page',2=>'CmsHardCodedPage',*/4=>'User',5=>'Project'),
		'SearchablesKeyword'=>array(/*0=>'PostsTag',1=>'PostsCategory'*/),
	),
	
	'config'=>array(
		'users.pseudo'=>false,
		'user.searchable'=>true,
		
		'searchable.statuses'=>true,
		'searchableHistory.source'=>true,
	)
);