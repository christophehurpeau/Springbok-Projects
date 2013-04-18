<?php
/** @TableAlias('ic') */
class IssuesCategory extends SSqlModel{
	public
		/** @Pk @AutoIncrement @SqlType('int(10) unsigned') @NotNull
		*/ $id,
		/** @SqlType('varchar(100)') @NotNull
		*/ $name,
		/** @SqlType('int(10) unsigned') @NotNull
		* @ForeignKey('Project','id','onDelete'=>'CASCADE')
		*/ $project_id,
		/** @SqlType('int(10) unsigned') @NotNull
		* @ForeignKey('ProjectMember','id','onDelete'=>'CASCADE')
		*/ $assign_to;
}