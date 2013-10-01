<?php
/** @TableAlias('aclg') @Created @Updated */
class AclGroup extends SSqlModel{
	/* @ImportTraits('acl','AclGroup') */
	/* @ImportConsts('acl','AclGroup') */
	/* @ImportFields('acl','AclGroup') */
	/* @ImportFunction('acl','AclGroup','#') */
	
	const MANAGER=3,DEVELOPPER=4,REPORTER=5;
	
	public static function afterCreateTable(){
		self::QInsert()->cols('id,name,left,right,level_depth')->mvalues(array(
			array(1,_tC('Guest'),0,0,0),
			array(2,_tC('Basic user'),0,0,0),
			array(3,_t('Manager'),0,0,0),
			array(4,_t('Developper'),0,0,0),
			array(5,_t('Reporter'),0,0,0),
		));
		self::rebuild();
	}
}