<?php
/** @TableAlias('p') @Child('Searchable') */
class Project extends Searchable{
	use BChild;
	
	const ACTIVE=1,ARCHIVED=9;
	const GITHUB=1;
	
	public
		/** @SqlType('text') @Null
		*/ $text,
		/** @SqlType('varchar(255)') @Null
		*/ $website,
		/** @Boolean
		*/ $public,
		/** @SqlType('int(10) unsigned') @Null
		* @ForeignKey('Project','id')
		*/ $parent_id,
		/** @SqlType('int(10) unsigned') @Null
		* @ForeignKey('Repository','id','onDelete'=>'SET NULL')
		*/ $repository_id,
		/** @SqlType('int(10) unsigned') @NotNull
		* @ForeignKey('User','id')
		*/ $owner;
	
	public static $hasOne=array('Repository');
	
	public static function findAll($user=false,$limit=null){
		$query=self::QAll()->withParent('name,slug')->orderBy(array('sb.created'=>'DESC'));
		$where=array('sb.status='.self::ACTIVE);
		self::_findConditions($query,$user,$where);
		if($limit!==null) $query->limit($limit);
		return $query->fetch();
	}
	
	public static function findOneById($id,$options=array()){
		$user=isset($options['user']) ? $options['user'] : null;
		if($user===null) $user=CSecure::user();
		$project=self::findOne($id,$user,isset($options['with']) ? $options['with'] : null);
		if($project===false && $user===false) CSecure::redirectToLogin();
		return $project;
	}
	
	public static function findOne($id=0,$user=false,$with=null){
		$query=self::QOne()->withParent('name,slug');
		$where=array('id'=>$id,'sb.status='.self::ACTIVE);
		self::_findConditions($query,$user,$where);
		if($with!==null) $query->setAllWith($with);
		return $query->fetch();
	}
	
	public static function _findConditions(&$query,&$user,&$where){
		if($user===false) $where['public']=true;
		elseif(!$user->isAdmin()){
			$query->with('ProjectMember',array('fields'=>false,'join'=>true,'onConditions'=>array('mbr.user_id'=>$user->id)));
			$where['OR']=array('public'=>true,'mbr.user_id'=>true);
		}
		$query->where($where);
	}
	
	public function repositoryLink($action='view',$path='',$more=null){
		return array('/:id-:slug/repository/:action(/:path)?',$this->id,$this->slug,_tR($action),$path,'?'=>$more);
	}
	public function issueLink($iid,$action){
		return array('/:id-:slug/issue/:iid(/:action)?',$this->id,$this->slug,$iid,_tR($action));
	}
	public function wikiLink($page='',$action=''){
		return array('/:id-:slug/wiki(/:page(/:action)?)?',$this->id,$this->slug,$page,_tR($action));
	}
	public function settingsLink($action=''){
		return array('/:id-:slug/settings(/:action/*)?',$this->id,$this->slug,_tR($action));
	}
	
	public function translationLink($action='',$more=''){
		return array('/:id-:slug/:controller(/:action/*)?',$this->id,$this->slug,_tR('translations'),_tR($action),$more);
	}
	

	private $_slug;
	public function slug(){
		if($this->_slug!==NULL) return $this->_slug;
		return $this->_slug=HString::slug($this->name);
	}
	
	public function isAdmin(){
		return $this->owner==CSecure::connected() || (CSecure::isConnected() && CSecure::isAdmin());
	}
	public function hasAccess($permission){
		return ACAcl::checkAccess($permission,$this->id);
	}
	public function hasRepository(){
		return $this->repository_id!==null;
	}
	
	public function isDescendantOf($id){
		return false;//TODO
	}
	
	public function isAncestorOf($id){
		return false;//TODO
	}
}