<?php
/** @TableAlias('rc')
* @IndexUnique('repository_id','name','email')
*/
class RepositoryCommitter extends SSqlModel{
	public
		/** @Pk @AutoIncrement @SqlType('int(10) unsigned') @NotNull
		*/ $id,
		/** @SqlType('int(10) unsigned') @NotNull
		* @ForeignKey('Repository','id','onDelete'=>'CASCADE')
		*/ $repository_id,
		/** @SqlType('varchar(255)') @NotNull
		*/ $name,
		/** @SqlType('varchar(255)') @NotNull
		*/ $email,
		/** @SqlType('int(10) unsigned') @Null
		* @ForeignKey('User','id')
		*/ $user_id;
	
	public static function getOrCreate($repositoryId,$committerName,$committerEmail){
		$rc=self::QOne()->byRepository_idAndNameAndEmail($repositoryId,$committerName,$committerEmail);
		if($rc!==false) return $rc;
		
		$rc=new RepositoryCommitter;
		$rc->repository_id=$repositoryId;
		$rc->name=$committerName;
		$rc->email=$committerEmail;
		$userId=User::QValue()->field('id')->where(array('OR'=>array('email LIKE'=>&$committerEmail,'CONCAT(first_name," ",last_name) LIKE'=>&$committerName)));
		if($userId!==false) $rc->user_id=$userId;
		$rc->insert();
		return $rc;
		
		//return self::QInsert()->set(array('repository_id'=>$repositoryId,'name'=>$committerName));
	}
}