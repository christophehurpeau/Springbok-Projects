<?php
/** @TableAlias('ip') */
class IssuesPriority extends SSqlModel{
	public
		/** @Pk @AutoIncrement @SqlType('tinyint(3) unsigned') @NotNull
		*/ $id,
		/** @SqlType('varchar(100)') @NotNull
		*/ $name,
		/** @Boolean
		*/ $default,
		/** @SqlType('tinyint(3) unsigned')
		*/ $position;
	
	public static function afterCreateTable(){
		self::QInsert()->cols('name,default,position')->mvalues(array(
			array('Bas',false,1),
			array('Normal',true,2),
			array('Haut',false,3),
			array('Urgent',false,4),
			array('Immédiat',false,5),
		));
	}
}