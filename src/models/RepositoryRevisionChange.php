<?php
/** @TableAlias('rrc') */
class RepositoryRevisionChange extends SSqlModel{
	public
		/** @Pk @AutoIncrement @SqlType('int(10) unsigned') @NotNull
		*/ $id,
		/** @SqlType('int(10) unsigned') @NotNull
		* @ForeignKey('RepositoryRevision','id','onDelete'=>'CASCADE')
		*/ $revision_id,
		/** @SqlType('ENUM("A","M","C","R","D")') @NotNull
		*/ $type,
		/** @SqlType('text') @NotNull
		*/ $path,
		/** @SqlType('text') @NotNull
		*/ $dirname,
		/** @SqlType('varchar(255)') @NotNull
		*/ $filename;
		
	public static function create($revisionId,$path,$type){
		$dirname=dirname($path);
		if(empty($dirname)){ $dirname='.'; $filename=$path; }
		else $filename=substr($path,strlen($dirname)+1);
		self::QInsert()->set(array('revision_id'=>$revisionId,'path'=>$path,'dirname'=>$dirname,'filename'=>$filename,'type'=>$type))->execute();
	}
}