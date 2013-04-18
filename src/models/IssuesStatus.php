<?php
/** @TableAlias('iss') */
class IssuesStatus extends SSqlModel{
	public
		/** @Pk @AutoIncrement @SqlType('tinyint(3) unsigned') @NotNull
		*/ $id,
		/** @SqlType('varchar(100)') @NotNull
		*/ $name,
		/** @Boolean @Default(false)
		*/ $closed,
		/** @Boolean @Default(false)
		*/ $default,
		/** @SqlType('tinyint(3) unsigned') @NotNull
		*/ $position;
		
	public static function afterCreateTable(){
		self::QInsert()->cols('name,closed,default,position')->mvalues(array(
			array('Nouveau',false,true,1),
			array('En cours',false,false,2),
			array('Résolu',false,false,3),
			array('Fermé',true,false,4),
			array('Rejeté',true,false,5),
		));
	}
	
	public static function isIssueClosed($statusId){
		return self::QValue()->field('closed')->byId($statusId) !== null;
	}
}