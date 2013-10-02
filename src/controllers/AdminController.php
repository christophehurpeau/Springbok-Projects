<?php
Controller::$defaultLayout='admin';
/** @Check(9) */
class AdminController extends Controller{
	/** */
	static function index(){
		render();
		
		//TODO settings : select status fix by rev : has to be a closed one. When update a status, has to be closed if is in settings.
	}
	
}