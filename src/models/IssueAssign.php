<?php
/** @TableAlias('ia') */
class IssueAssign extends SSqlModel{
	public
		/** @Pk @SqlType('int(10) unsigned') @NotNull
		* @ForeignKey('Issue','id','onDelete'=>'CASCADE')
		*/ $issue_id,
		/** @Pk @SqlType('int(10) unsigned') @NotNull
		* @ForeignKey('ProjectMember','id','onDelete'=>'CASCADE')
		*/ $member_id,
		/** @SqlType('datetime') @NotNull
		*/ $created;
	
	public static $hasOneThrough=array('User'=>array('joins'=>'ProjectMember','fields'=>'id,first_name,last_name'));
}