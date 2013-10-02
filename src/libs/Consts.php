<?php
class AConsts{
	/* SOURCES */
	const SITE=0,REPOSITORY=1,
		DEFAULT_SOURCE=0;
	
	/* STATUS */
	const INVALID=0,VALID=1,DELETED=2,MERGED=3,CLOSED=5;
	
	
	public static function searchableStatuses(){
		return array(self::INVALID=>'Invalide',self::VALID=>'Valide',self::DELETED=>'Supprimé',self::MERGED=>'Fusionné',self::CLOSED=>'Fermé');
	}
	
	
	public static function sources(){
		return array(self::SITE=>'Site',self::REPOSITORY=>'Repository');
	}
}
