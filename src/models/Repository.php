<?php
/** @TableAlias('r') */
class Repository extends SSqlModel{
	CONST SVN=1,GIT=2,GITHUB=21;
	public
		/** @Pk @AutoIncrement @SqlType('int(10) unsigned') @NotNull 
		*/ $id,
		/** @SqlType('int(10) unsigned') @NotNull
		* @ForeignKey('Project','id') 
		*/ $project_id,
		/** @SqlType('tinyint(2) unsigned') @Null
		 * @Enum(1=>'SVN',2=>'Git',3=>'GitHub')
		*/ $type,
		/** @SqlType('varchar(255)') @NotNull 
		*/ $path,
		/** @SqlType('varchar(255)') @Null 
		*/ $source,
		/** @SqlType('text') @Null @Json
		*/ $extra_infos;
	
	public static $belongsTo=array('Project');
	
	public static function helps(){
		return array(self::SVN=>'SVN : just enter your path.',
		self::GIT=>'GIT : just enter your path.',
		self::GITHUB=>'Enter the url of the project without "https://github.com/" : ex : "christophehurpeau/Springbok-Framework".');
		/*
		 * Go to the repository Admin interface on Github.
		 * Under “Service Hooks” add a new “Post-Receive URL” of the format: “[redmine_installation_url]/repo/update/” (for example “http://example.com/github_hook”).
		 */
	}
	
	public function path(){
		if($this->type===self::GITHUB) return Config::$repositories_path.'github-repositories/'.$this->path;
		return Config::$repositories_path.$this->path;
	}
	
	private $_open;
	public function open(){
		if($this->_open!==null) return $this->_open;
		if($this->type===self::GIT) return $this->_open=UGit::open($this->path());
		if($this->type===self::GITHUB) return $this->_open=UGitHub::open($this->path);
	}
	
	public function check(){
		if($this->path[0]==='/'||stripos($this->path,'://')){
			$source=$this->source=$this->path;
			$this->path='project_'.$this->project_id;
		}else $source=null;
		if($this->type===self::GIT) return UGit::check($this->path(),$source);
		if($this->type===self::GITHUB) return UGitHub::check($this->path);
	}

	public function remove(){
		if($this->source!==null){
			$path=$this->path();
			for($i=0; ;$i++){
				if(!file_exists($bakPath=($path.'.bak'.($i===0?'':$i)))){
					UExec::exec('mv '.escapeshellarg($path).' '.escapeshellarg($bakPath));
					return true;
				}
			}
		}
		return false;
	}
	
	public function entries($path,$identifier){
		return $this->open()->entries($path,$identifier);
	}
	
	public function branches(){
		return $this->open()->branches();
	}
	
	public function fetch(){
		$this->open()->fetch();
		$this->fetchRevisions();
	}
	
	public function fetchRevisions(){
		if(!empty($this->extra_infos['fetching'])) return;
		if($this->extraInfos===null) $this->extraInfos=array();
		$this->extraInfos['fetching']=1;
		$this->update('extra_infos');
		
		$branches=$this->branches();
		if(empty($branches)) return;
		
		if(!isset($this->extraInfos['branches'])) $this->extraInfos['branches']=array();
		
		foreach($branches as $name=>&$branch){
			if(!isset($this->extraInfos['branches'][$name])){
				$this->extraInfos['branches'][$name]=array();
				$from=null;
			}else $from=isset($this->extraInfos['branches'][$name]['last_id']) ? $this->extraInfos['branches'][$name]['last_id'] : null;
			$revisions=$this->_open->revisions('',$from,$name,array('reverse'=>true));
			foreach($revisions as $rev){
				$dbRev=RepositoryRevision::existByRevision($rev->id);
				self::beginTransaction();
				if($dbRev===false) $dbRev=RepositoryRevision::create($this,$rev);
				$this->extraInfos['branches'][$name]['last_id']=$rev->id;
				$this->update('extra_infos');
				self::commit();
			}
		}
		unset($this->extraInfos['fetching']);
		$this->extraInfos['last_fetch']=date('Y-m-d H:i:s');
		$this->update('extra_infos');
	}

	
	
	
	public function latestRevisions($path,$rev,$limit=14){
		return $this->open()->shortRevisions($path,null,$rev,array('limit'=>$limit,'all'=>false));
	}
}