<?php
class VProjectLatest extends SViewElement{
	public static function vars(){
		return array(
			'projects'=>Project::findAll(CSecure::user(),8)
		);
	}
}
