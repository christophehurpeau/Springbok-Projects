<?php
/** @TableAlias('aclg') @Created */
class AclGroup extends SSqlModel{
	/* @ImportTraits('acl','AclGroup') */
	/* @ImportConsts('acl','AclGroup') */
	/* @ImportFields('acl','AclGroup') */
	/* @ImportFunction('acl','AclGroup','#') */
	
	const MANAGER=3,DEVELOPPER=4,REPORTER=5;
	
	public static function afterCreateTable(){
		self::QInsert()->cols('id,name')->mvalues(array(
			array(1,_tC('Guest')),
			array(2,_tC('Basic user')),
			array(3,_t('Manager')),
			array(4,_t('Developper')),
			array(5,_t('Reporter')),
		));
	}
}