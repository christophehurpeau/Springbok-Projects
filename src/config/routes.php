<?php return array(
	'/'=>array('Site::index'),
	
	'/:id-:slug'=>array('Searchable::view',NULL,array('fr'=>'/:id-:slug')),
	'/:id-:slug/repository/:action(/:path)?'=>array('Repository::index',array('path'=>'.+')),
	
	'/:id-:slug/:controller(/:action/*)?'=>array('Site::index',array('controller'=>'issues|translations')),
	'/:id-:slug/issue/:iid(/:action)?'=>array('Issue::index',array('iid'=>'[0-9]+'),array('fr'=>'/:id-:slug/demande/:iid(/:action)?')),
	
	'/:id-:slug/wiki(/:page(/:action)?)?'=>array('Wiki::index',array(),array('fr'=>'/:id-:slug/wiki(/:page(/:action)?)?')),
	
	'/:id-:slug/:action/*'=>array('Project::index',NULL,array('fr'=>'/:id-:slug/:action/*')),
	
	'/:controller(/:action/*)?'=>array('Site::index'),
);
