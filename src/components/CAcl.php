<?php
class ACAcl extends CAcl{
	public static function checkAccess($permission,$projectId=null){
		if(CSecure::isConnected()){
			if(CSecure::isAdmin()) return true;
			
			$roleId=AclGroup::BASIC_USER;
			
			if($projectId===null)
				$role_id=ProjectMemberRole::QValues()->field('DISTINCT role_id')->with('ProjectMember',false)
						->where(array('mbr.user_id'=>CSecure::connected()));
			else
				$role_id=ProjectMemberRole::QValues()->field('role_id')->with('ProjectMember',false)
						->where(array('mbr.project_id'=>$projectId,'mbr.user_id'=>CSecure::connected()));
			
			if($role_id!==false){
				$roleId=$role_id;
				$roleId[]=AclGroup::GUEST;
				$roleId[]=AclGroup::BASIC_USER;
			}
		}else $roleId=AclGroup::GUEST;
		return AclGroupPerm::QExist()->where(array('granted'=>true,'group_id'=>&$roleId,'permission'=>&$permission));
	}
}