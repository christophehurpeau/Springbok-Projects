<?php
class UserController extends Controller{
	/* @ImportAction('users','','User','#') */
	/* @ImportFunction('users','','User','#') */
	
	/** @SubAction('Searchable') */
	static function view($user){
		$query=Project::QAll()->withParent()->orderBy(array('sb.created'=>'DESC'))->with('ProjectMember',array('fields'=>false,'join'=>true));
		$where=array('mbr.user_id'=>$user->id);
		$connectedUser=CSecure::user();
		Project::_findConditions($query,$connectedUser,$where);
		$user->projects=$query->groupBy('p.id')->tabResKey()->execute();
		
		if(!empty($user->projects)){
			$paginate=$user->findWithPaginate('SearchableHistory',array('dataName'=>'history','with'=>array('details','Project'=>array('with'=>array('Parent'))),
				'where'=>array('searchable_id'=>array_keys($user->projects)),'orderBy'=>array('created'=>'DESC')));
			$paginate->pageSize(12)->execute();
		}
		
		render();
	}
}