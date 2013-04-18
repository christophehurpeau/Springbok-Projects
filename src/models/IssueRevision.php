<?php
/** @TableAlias('ir') @IndexUnique('issue_id','revision_id') */
class IssueRevision extends SSqlModel{
	public
		/** @Pk @AutoIncrement @SqlType('int(10) unsigned') @NotNull
		*/ $id,
		/** @SqlType('int(10) unsigned') @NotNull
		* @ForeignKey('Issue','id','onDelete'=>'CASCADE')
		*/ $issue_id,
		/** @SqlType('int(10) unsigned') @NotNull
		* @ForeignKey('RepositoryRevision','id','onDelete'=>'CASCADE')
		*/ $revision_id,
		/** @Boolean @Default(false)
		*/ $closed;
	
	public static $belongsTo=array('RepositoryRevision'=>array('dataName'=>'revision','foreignKey'=>'revision_id'));
	
	public static $hasOneThrough=array(
		'Repository'=>array('joins'=>'RepositoryRevision'),
		'Project'=>array('joins'=>'Repository,RepositoryRevision')
	);
	
	public static function create($issueId,$revisionId,$closed){
		if(!($res=self::QInsert()->ignore()->set(array('issue_id'=>$issueId,'revision_id'=>$revisionId,'closed'=>$closed)))){
			if($closed)
				self::QUpdateOneField('closed',true)->where(array('issue_id'=>$issueId,'revision_id'=>$revisionId))->limit1();
		}
		return $res;
	}
	
	public function detailsFromProject($project){
		$this->revision->_setRef('committer',$this->_getRef('committer'));
		$this->revision->_setRef('user',$this->_getRef('user'));
		return $this->issue->linkToIssue($project).' '.h(_t('by')).' '.$this->revision->committer()
				.' ('._t('Revision:').' '.$this->revision->linkToRevision($project).')';
	}
	
	public function detailsFromUser($project){
		$this->revision->_setRef('committer',$this->_getRef('committer'));
		$this->revision->_setRef('user',$this->_getRef('user'));
		return $this->issue->linkToIssue($project).' '.h(_t('by')).' '.$this->revision->committer()
				.' ('._t('Revision:').' '.$this->revision->linkToRevision($project).')';
	}
}