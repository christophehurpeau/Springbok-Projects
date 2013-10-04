<?php
/** @TableAlias('it') */
class IssuesTracker extends SSqlModel{
	public
		/** @Pk @AutoIncrement @SqlType('tinyint(3) unsigned') @NotNull
		*/ $id,
		/** @SqlType('varchar(100)') @NotNull
		*/ $name,
		/** @Boolean
		*/ $in_chglog,
		/** @Boolean
		*/ $default,
		/** @SqlType('tinyint(3) unsigned')
		*/ $position;
		
	public static function afterCreateTable(){
		self::QInsert()->cols('name,in_chglog,default,position')->mvalues(array(
			array('Evolution',true,true,1),
			array('Anomalie',true,false,2),
			array('Assistance',false,false,3),
		))->execute();
	}
}