<?php
class ProjectController extends AController{
	/** */
	static function index(){
		set('projects',Project::findAll(CSecure::user()));
		render();
	}
	
	/** @Check @Acl('CreateProject')
	* project > @Valid('name','text','website','public')
	*/ function add(Project $project){
		if($project !== NULL){
			if(!CValidation::hasErrors()){
				$project->owner=CSecure::connected();
				$project->visible=true;
				$project->insert();
				ProjectMember::create($project->id,$project->owner,array(AclGroup::MANAGER));
				self::redirect($project->link());
			}//else echo CValidation::errors();
		}
		render();
	}
	
	/** @SubAction('Searchable') */
	static function view($project){
		$project->findWith('History',array('orderBy'=>array('created'=>'DESC'),'limit'=>8));
		render();
	}
	
	/** @Check @ValidParams
	* repository > @Valid('type','path')
	*/ function repository_add(Repository $repository){
		$project=AController::findProject('RepositoryManage');
		if($repository!==null && !CValidation::hasErrors()){
			$repository->project_id=$project->id;
			if($repository->check()){
				$repository->insert();
				Project::updateOneFieldByPk($project->id,'repository_id',$repository->id);
				redirect($project->repositoryLink());
			}else set('error','This repository doesn`t exist.');
		}
		render();
	}
	
	
	/** @ValidParams */
	static function members(){
		$project=AController::findProject('SeeMembers');
		$project->findWith('ProjectMember',array('with'=>array('User'=>array('with'=>array('Parent')),'Role')));
		if($project->isAdmin()){
			$query=User::QListName();
			if(!empty($project->members)) $query->where(array('id NOTIN'=>array_map(function(&$member){return $member->user->id;},$project->members)));
			set('potentialNewMembers',$query->fetch());
		}
		render();
	}
	
	/** @Check @ValidParams
	* user_id > @Type(array[]int)
	* role_id > @Type(array[]int)
	*/
	static function addMembers($user_id,$role_id){
		$project=AController::findProject('ManageMembers');
		if(!$project->isAdmin()) forbidden();
		foreach($user_id as $userId)
			ProjectMember::create($project->id,$userId,$role_id);
		redirect($project->link('members',''));
	}
	
	/** @Check @ValidParams @Id('idMember')
	* roles > @Type(array[]int)
	*/
	static function editMember(int $idMember,$roles){
		$project=AController::findProject('ManageMembers');
		if(!$project->isAdmin()) forbidden();
		if(!empty($roles)){
			ProjectMemberRole::QDeleteAll()->byMember_id($idMember);
			foreach($roles as $roleId)
				ProjectMemberRole::QInsert()->ignore()->set(array('member_id'=>$idMember,'role_id'=>$roleId));
			redirect($project->link('members',''));
		}
		set('member',ProjectMember::ById($idMember)->with('ProjectMemberRole','role_id')->with('User','first_name,last_name'));
		render();
	}
	
	/** @ValidParams */
	static function activity(){
		$project=AController::findProject('ProjectActivity');
		$paginate=$project->findWithPaginate('History',array('with'=>array('details'),'orderBy'=>array('created'=>'DESC')));
		$paginate->pageSize(25)->execute();
		render();
	}
}