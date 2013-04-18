<?php
/** @TableAlias('i') */
class Issue extends SSqlModel{
	public
		/** @Pk @AutoIncrement @SqlType('int(10) unsigned') @NotNull
		*/ $id,
		/** @SqlType('int(10) unsigned') @NotNull
		* @ForeignKey('Project','id','onDelete'=>'CASCADE')
		* @NotBindable
		*/ $project_id,
		/** @SqlType('tinyint(3) unsigned') @NotNull
		* @ForeignKey('IssuesTracker','id','onDelete'=>'CASCADE')
		*/ $tracker_id,
		
		/** @SqlType('varchar(255)') @NotNull
		*/ $title,
		/** @SqlType('text') @Null
		*/ $description,
		
		/** @SqlType('int(10) unsigned') @NotNull
		* @ForeignKey('User','id','onDelete'=>'CASCADE')
		* @NotBindable
		*/ $author_id,
		/** @SqlType('int(10) unsigned') @Null
		* @ForeignKey('IssuesCategory','id','onDelete'=>'CASCADE')
		*/ $category_id,
		/** @SqlType('tinyint(3) unsigned') @Null
		* @ForeignKey('IssuesStatus','id','onDelete'=>'CASCADE')
		*/ $status_id,
		/** @SqlType('tinyint(3) unsigned') @Null
		* @ForeignKey('IssuesPriority','id','onDelete'=>'CASCADE')
		*/ $priority_id,
		/** @SqlType('int(10) unsigned') @Null
		* @ForeignKey('ProjectVersion','id','onDelete'=>'CASCADE')
		*/ $version_id,
		
		/** @SqlType('date') @Null
		*/ $start_date,
		/** @SqlType('date') @Null
		*/ $due_date,
		/** @SqlType('tinyint(3) unsigned') @NotNull @Default(0)
		*/ $done_ratio,
		/** @SqlType('decimal(9,2) unsigned') @Null
		*/ $estimated_hours,
		
		/** @SqlType('int(10) unsigned') @Null
		*/ $parent_id,
		
		/** @SqlType('datetime') @NotNull
		*/ $created,
		/** @SqlType('datetime') @Null
		*/ $updated;
	
	/*
	 * fixed_version_id	lock_version		parent_id	root_id	lft	rgt	is_private
	 */
	
	public static $belongsTo=array(
		'Author'=>array('modelName'=>'User','dataName'=>'author','foreignKey'=>'author_id'),
		'IssuesTracker'=>array('dataName'=>'tracker','foreignKey'=>'tracker_id'),
		'IssuesCategory'=>array('dataName'=>'category','foreignKey'=>'category_id'),
		'IssuesStatus'=>array('dataName'=>'status','foreignKey'=>'status_id'),
		'IssuesPriority'=>array('dataName'=>'priority','foreignKey'=>'priority_id'),
		'ProjectVersion'=>array('dataName'=>'version','foreignKey'=>'version_id'),
	);
	public static $hasManyThrough=array(
		'RepositoryRevision'=>array('dataName'=>'revisions','joins'=>'IssueRevision'),
	);
	
	protected function beforeSave(){
		if($this->start_date==='') $this->start_date=null;
		if($this->due_date==='') $this->due_date=null;
		if($this->estimated_hours==0) $this->estimated_hours=null;
		if(empty($this->description)) $this->description=null;
		return true;
	}

	protected function afterInsert(){
		ProjectHistory::add($this->project_id,ProjectHistory::ISSUE_NEW,$this->id,$this->author_id);
	}
	
	
	public function linkToIssue($project){
		return HHtml::link($this->title,$project->getIssueLink('view',$this->id));
	}
	public function detailsFromProject($project){
		return $this->linkToIssue($project).' '.h(_t('by')).' '.h($this->author->name());
	}
	public function detailsFromUser($project){
		return $this->linkToIssue($project).' '.h(_t('for')).' '.HHtml::link($project->name,$project->link());
	}
	public function moreDetails(){
		return $this->description;
	}
	
	public function isEditable($project=null){
		return $project->hasAccess('IssueEdit') || ($this->author_id===CSecure::connected() && $project->hasAccess('IssueEditOwn'));
	}


	public function fixByRevision($irId,$hours,$userId,$revision){
		$status=CSettings::get('Repository.commit.fixStatusId');
		if(!empty($status)){
			if(!IssuesStatus::isIssueClosed($this->status_id)){
				$this->status_id=$status;
				$this->update('status_id');
				ProjectHistory::add($this->project_id,ProjectHistory::ISSUE_CLOSE_BY_REV,$irId,$userId);
			}
		}
		
		$doneRatio=CSettings::get('Repository.commit.fixDoneRatio');
		if(!empty($doneRatio) && $this->done_ratio!=$doneRatio && $this->done_ratio<$doneRatio){
			$this->done_ratio=$doneRatio;
			$this->update('done_ratio');
		}
		
		$this->logTimeByRevision($irId,$hours,$userId,$revision);
	}

	public function logTimeByRevision($irId,$hours,$userId,&$revision){
		if(empty($hours)) return;
		$hours=UTime::parseHoursDuration($hours);
		if($hours===false) return;
		$hours=round($hours,2);
		
		ProjectTimeEntry::QInsert()->set(array(
			'project_id'=>$this->project_id,
			'user_id'=>$userId,
			'issue_id'=>$this->id,
			'revision_id'=>$revision->id,
			'activity_id'=>CSettings::get('Repository.commit.timeActivityId'),
			'hours'=>$hours,
			'spent_on'=>$revision->committed,
		));
	}
}