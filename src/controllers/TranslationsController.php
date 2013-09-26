<?php
/** */
class TranslationsController extends AController{
	/** */
	static function index(){
		$project=AController::findProject('SeeTranslations',array('Repository'=>array('type'=>QFind::INNER)));
		
		$repo=$project->repository->open();
		$config=$repo->cat('src/config/_.php');
		if(empty($config)) self::_error('config file "_.php" not found');
		eval('$config='.substr($config,12)); //<?php return array(
		if(empty($config['availableLangs'])) self::_error('no "availableLangs" in config file "_.php"');
		set('langs',$config['availableLangs']);
		
		render();
	}
	
	
	private static function _error($error){
		mset($error);
		render('error_page','Project');
		exit;
	}
	
	
	/** @NotEmpty('lang') */
	static function project($lang){
		$project=AController::findProject('SeeTranslations',array('Repository'=>array('type'=>QFind::INNER)));
		mset($lang);
		
		render();
	}
}