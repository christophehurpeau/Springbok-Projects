<?php
class AController extends Controller{
	protected static function beforeDispatch(){
		CSecure::connect(false);
	}
	
	public static function findProject($requiredPermission=null,$with=null){
		$project=ACSearchable::find('Project',array('with'=>$with),false);
		if($requiredPermission!==null) ACAcl::requireAccess($requiredPermission,$project->id);
		return $project;
	}
}
