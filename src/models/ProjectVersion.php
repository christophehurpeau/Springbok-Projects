<?php
/** @TableAlias('pv') */
class ProjectVersion extends SSqlModel{
	public
		/** @Pk @AutoIncrement @SqlType('int(10) unsigned') @NotNull
		*/ $id,
		/** @SqlType('int(10) unsigned') @NotNull
		* @ForeignKey('Project','id') 
		*/ $project_id,
		/** @SqlType('varchar(255)') @NotNull
		*/ $name,
		/** @SqlType('datetime') @NotNull
		*/ $created,
		/** @SqlType('datetime') @Null
		*/ $updated;
}