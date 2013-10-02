<?php
Controller::$defaultLayout='admin';
/** @Check(9) */
class AdminProjectsController extends Controller{
	/** */
	static function index(){
		Project::Table()->fields('id,name,public,status,owner,created,updated')->paginate()->render(_t('Projects'));
	}
}