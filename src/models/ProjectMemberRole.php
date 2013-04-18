<?php
/** @TableAlias('pmr') */
class ProjectMemberRole extends SSqlModel{
	public
		/** @Pk @SqlType('int(10) unsigned') @NotNull
		* @ForeignKey('ProjectMember','id')
		*/ $member_id,
		/** @Pk @SqlType('tinyint(3) unsigned') @NotNull
		* @ForeignKey('AclGroup','id')
		*/ $role_id;
}