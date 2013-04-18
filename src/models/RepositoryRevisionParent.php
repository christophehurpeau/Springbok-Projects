<?php
/** @TableAlias('rrp') */
class RepositoryRevisionParent extends SSqlModel{
	public
		/** @Pk @SqlType('int(10) unsigned') @NotNull
		* @ForeignKey('RepositoryRevision','id','onDelete'=>'CASCADE')
		*/ $revision_id,
		/** @Pk @SqlType('int(10) unsigned') @NotNull
		* @ForeignKey('RepositoryRevision','id','onDelete'=>'CASCADE')
		*/ $parent_id;
}