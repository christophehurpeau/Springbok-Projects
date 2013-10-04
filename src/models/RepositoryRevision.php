<?php
/** @TableAlias('rr') */
class RepositoryRevision extends SSqlModel{
	public
		/** @Pk @AutoIncrement @SqlType('int(10) unsigned') @NotNull
		*/ $id,
		/** @SqlType('int(10) unsigned') @NotNull
		* @ForeignKey('Repository','id','onDelete'=>'CASCADE')
		*/ $repository_id,
		/** @SqlType('varchar(255)') @NotNull
		*/ $revision,
		/** @SqlType('int(10) unsigned') @NotNull
		* @ForeignKey('RepositoryCommitter','id')
		*/ $committer_id,
		/** @SqlType('datetime') @NotNull
		*/ $committed,
		/** @SqlType('text') @NotNull
		*/ $comment;
	
	public static $belongsTo=array(
		'Committer'=>array('modelName'=>'RepositoryCommitter','foreignKey'=>'committer_id',
			'fields'=>'name,user_id','with'=>array('User'=>array('fields'=>'id','with'=>array('Parent'))))
	);
	public static $hasMany=array(
		'RevisionParent'=>array('modelName'=>'RepositoryRevisionParent','associationForeignKey'=>'revision_id'),
		'RevisionChild'=>array('modelName'=>'RepositoryRevisionParent','associationForeignKey'=>'parent_id'),
	);
	public static $hasManyThrough=array(
		'Parents'=>array('modelName'=>'RepositoryRevision','joins'=>array('RevisionParent')),
		'Children'=>array('modelName'=>'RepositoryRevision','joins'=>array('RevisionChild')),
	);
	
	public static $hasOneThrough=array(
		'Project'=>array('joins'=>'Repository')
	);
	
	public static function create(&$repository,&$rev){
		$revision=new RepositoryRevision;
		$revision->repository_id=$repository->id;
		$revision->revision=$rev->id;
		$revision->committer=RepositoryCommitter::getOrCreate($repository->id,$rev->authorName,$rev->authorEmail);
		$revision->committer_id=$revision->committer->id;
		$revision->committed=date('Y-m-d H:i:s',$rev->time);
		$revision->comment=$rev->comment;
		$revision->findWith('Committer');
		if($revision->insert()){
			SearchableHistory::add($repository->project_id,SearchableHistory::REPOSITORY_NEW_REVISION,
							$revision->id,$revision->committer->user_id,AConsts::REPOSITORY,$revision->committed);
			
			foreach($rev->files as $path=>$type)
				RepositoryRevisionChange::create($revision->id,$path,$type);
			
			if($rev->parents !==null) foreach($rev->parents as $parent){
				$rp=new RepositoryRevisionParent;
				$rp->revision_id=$revision->id;
				$rp->parent_id=RepositoryRevision::findValueIdByRevision($parent);
				if($rp->parent_id!==null) $rp->insert();
			}
		}
		return $revision;
	}

	public static function findOneInfos($rId,$rev){
		return self::QOne()->byRepository_idAndRevision($rId,$rev)->with('Committer')->fetch();
	}
	
	public function afterInsert(){
		$this->scanForIssue();
	}
	
	public function scanForIssue(){
		if(empty($this->comment)) return;
		$refKeywords=CSettings::get('Repository.commit.refKeywords'); //TODO : set settings : trim, explode(','
		$fixKeywords=CSettings::get('Repository.commit.fixKeywords');
		
		$issueR='#(\d+)(?:\s+'.UTime::REG_EXPR_HOURS_MINUTES.')?';
		foreach(array('logTimeByRevision'=>implode('|',array_map('preg_quote',$refKeywords)),
					'fixByRevision'=>implode('|',array_map('preg_quote',$fixKeywords))) as $method=>$keywords){
			if(preg_match_all('/(?:'.$keywords.')\s+'.$issueR.'([\s,;&]+'.$issueR.')*/i',$this->comment,$m)){
				$this->findWith('Repository',array('fields'=>'id,project_id','with'=>array('Project'=>array('fields'=>'id,parent_id'))));
				$project=$this->repository->project;
				foreach($m[0] as $m0){
					if(preg_match_all('/'.$issueR.'/i',$m0,$mI)){
						foreach($mI[1] as $i=>$issueId){
							$issueId=(int)$issueId;
							$issue=Issue::ById($issueId)->fetch();
							if($issue===false || !($issue->project_id===$this->repository->project_id 
									|| $project->isAncestorOf($issue->project_id)
									|| $project->isDescendantOf($issue->project_id))) continue;
							$irId=IssueRevision::create($issue->id,$this->id,$method==='fixByRevision');
							$issue->$method($irId,$mI[2][$i],$this->committer->user_id,$this);
						}
					}
				}
			}
		}
		
	}


	public function committer(){
		return $this->committer->user_id===null ? '<i>'.h($this->committer->name).'</i>' : $this->user->linkedName();
	}
	
	public function linkToRevision($project){
		return HHtml::link($this->revision,$project->repositoryLink('revision',$this->revision));
	}
	
	public function detailsFromProject($project){
		return $this->linkToRevision($project).' '.h(_t('by')).' '.$this->committer();
	}
	public function detailsFromUser($project){
		return $this->linkToRevision($project).' '.h(_t('for')).' '.HHtml::link($project->name,$project->link());
	}
	public function moreDetails(){
		return nl2br($this->comment);
	}
	
	public function firstLineComment(){
		$firstLine=strstr($this->comment,"\n",true);
		return $firstLine===false ? $this->comment : $firstLine;
	}
}