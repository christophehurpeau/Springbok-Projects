<?php
/** @TableAlias('mbr') @Created @IndexUnique('user_id','project_id') */
class ProjectMember extends SSqlModel{
	public
		/** @Pk @AutoIncrement @SqlType('int(10) unsigned') @NotNull 
		*/ $id,
		/** @SqlType('int(10) unsigned') @NotNull
		* @ForeignKey('User','id') 
		*/ $user_id,
		/** @SqlType('int(10) unsigned') @NotNull
		* @ForeignKey('Project','id') 
		*/ $project_id,
		/** @Boolean
		*/ $mail_notification;
	
	
	public static $belongsTo=array(
		'User'=>array('fields'=>'id,first_name,last_name')
	);
	public static $hasManyThrough=array(
		'Role'=>array('modelName'=>'AclGroup','joins'=>'ProjectMemberRole','fields'=>array('name'))
	);
	
	public function roles(){
		return implode(', ',$this->roles);
	}
	
	public static function byProject($idProject){
		return self::QAll()->byProject_id($idProject);
	}
	
	public static function findListNameByProject($idProject){
		return self::QList()->field('id')->with('User',array('fields'=>array('CONCAT(first_name," ",last_name)')))->byProject_id($idProject);
	}
	
	public static function create($projectId,$userId,$roleIds){
		$pmId=self::QInsert()->set(array('project_id'=>$projectId,'user_id'=>$userId));
		if($pmId)
			foreach($roleIds as $roleId)
				ProjectMemberRole::QInsert()->set(array('member_id'=>$pmId,'role_id'=>$roleId));
	}
}