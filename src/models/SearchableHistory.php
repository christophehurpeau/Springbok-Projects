<?php
/** */
class SearchableHistory extends SSqlModel{
	/* @Extends('searchable','SearchableHistory') */
	
	public static $history=array(
		RepositoryRevision=>array(60=>REPOSITORY_NEW_REVISION),
		Issue=>array(70=>ISSUE_NEW, 71=>ISSUE_EDIT, 72=>ISSUE_CLOSED),
		IssueRevision=>array(73=>ISSUE_CLOSE_BY_REV),
	);
	
	public static $historyRelations=array(
		RepositoryRevision=>array('with'=>array('Committer')),
		Issue=>array('with'=>array('Author'=>array('fields'=>'id,first_name,last_name'))),
		IssueRevision=>array('with'=>array(
			'Issue'=>array(),
			'RepositoryRevision'=>array('with'=>array('Committer'))
		))
	);
	
	public static $belongsTo=array(
		'Project'=>array(array('searchable_id'=>'id'))
	);
	
	
	public function detailsFromProject($project){
		return ' : '.$this->details->detailsFromProject($project);
	}
	public function detailsFromUser($project){
		return ' : '.$this->details->detailsFromUser($project);
	}
}