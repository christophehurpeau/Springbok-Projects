<?php
class RepositoryController extends AController{
	/** @ValidParams @Id */
	function update(int $id){
		$repository=Repository::findOneByProject_id($id);
		notFoundIfFalse($repository);
		$repository->fetch();
		renderText('1');
	}
	
	/** @ValidParams */
	function view($path,$rev){
		$project=AController::findProject('RepositoryView');
		if($project->hasRepository()){
			$repository=Repository::findOneByProject_id($project->id);
			if(CSettings::get('Repository.autofetchChangesets')) $repository->fetchRevisions();
			$entries=$repository->entries($path,$rev);
			$latestRevisions=$repository->latestRevisions($path,$rev);
			mset($path,$entries,$latestRevisions);
			render();
		}else{
			render('inexistant');
		}
	}
	
	/** @ValidParams */
	function file_history($path){
		$project=AController::findProject('RepositoryView',array('Repository'));
		if($project->repository===false) notFound();
		$history=$project->repository->open()->fileHistory($path,null,null,array('limit'=>'30'));
		mset($path,$history);
		render();
	}
	
	/** @ValidParams */
	function file($path,$rev,$download){
		$project=AController::findProject('RepositoryView',array('Repository'));
		if($project->repository===false) notFound();
		$content=$project->repository->open()->cat($path,$rev);
		
		if($download!==null) self::sendText($content,basename($path));
		else{
			self::_setBrush($path);
			mset($rev,$content,$path);
			render();
		}
	}
	
	/** @ValidParams */
	function file_diff($path,$rev,$branch,$download){
		$project=AController::findProject('RepositoryView',array('Repository'));
		if($project->repository===false) notFound();
		$content=$project->repository->open()->diff($path,$rev,$branch);
		$content=substr(strstr(substr(strstr(substr(strstr(substr(strstr($content,"\n"),1),"\n"),1),"\n"),1),"\n"),1);
		
		if($download!==null) self::sendText($content,basename($path));
		else{
			self::_setBrush($path);
			mset($rev,$content,$path);
			render();
		}
	}
	
	private static function _setBrush($path){
		$ext=pathinfo($path,PATHINFO_EXTENSION);
		if($ext==='php') self::set('brush','php html-script:true');
		elseif($ext==='rb') self::set('brush','ruby');
		elseif(in_array($ext,array('c','cpp','css','delphi','pas','diff','patch','erl','groovy','js',
			'java','jfx','perl','pl','php','py','ruby','scala','sql','xml','xhtml','xslt','html'))) self::set('brush',$ext);
		else self::set('brush','text');
	}
	
	
	/** @Check @ValidParams
	* committers > @Type(array[]int)
	*/ function committers($committers){
		$project=AController::findProject('RepositoryManage',array('Repository'));
		if(!$project->hasRepository()) notFound();
		if($committers!==null){
			/*UserHistory::QDeleteAll()->where(array('type'=>AHistory::REPOSITORY_NEW_REVISION,
				'EXISTS (SELECT 1 FROM repository_revisions rr WHERE rr.id=rel_id AND repository_id='.$rId.')'));
			UserHistory::QDeleteAll()->where(array('type'=>AHistory::ISSUE_CLOSE_BY_REV,
				'EXISTS (SELECT 1 FROM issue_revisions ir LEFT JOIN repository_revisions rr ON ir.revision_id=rr.id'
					.' WHERE rr.id=rel_id AND repository_id='.$rId.')'));
			*/
			foreach($committers as $cId=>$uId){
				RepositoryCommitter::QUpdateOneField('user_id',e($uId,null))->byIdAndRepository_id($cId,$project->repository_id);
			}
			
			$COMMON_SQL='UPDATE searchable_history ph'
				.' LEFT JOIN repository_revisions rr ON rr.id= ph.rel_id'
				.' LEFT JOIN repository_committers rc ON rc.id=rr.committer_id';
			$COMMON_SQL_WHERE=' WHERE ph.searchable_id='.$id.' AND rc.repository_id='.$project->repository_id;
			$userIds=implode(',',$committers);
			$db=SearchableHistory::$__modelDb;
			$db->doUpdate($COMMON_SQL
				.' SET ph.user_id=rc.user_id'
				.$COMMON_SQL_WHERE.' AND ph.type='.SearchableHistory::REPOSITORY_NEW_REVISION
			);
			$db->doUpdate($COMMON_SQL
				.' LEFT JOIN issue_revisions ir ON ir.revision_id=rr.id AND ir.issue_id=ph.rel_id'
				.' SET ph.user_id=rc.user_id'
				.$COMMON_SQL_WHERE.' AND ph.type='.SearchableHistory::ISSUE_CLOSE_BY_REV
			);
			$db->doUpdate('UPDATE project_time_entries pte'
				.' LEFT JOIN repository_revisions rr ON rr.id=pte.revision_id'
				.' LEFT JOIN repository_committers rc ON rc.id=rr.committer_id'
				.' SET pte.user_id=rc.user_id'
				.' WHERE pte.project_id='.$id.' AND rr.repository_id='.$rId.' AND rc.repository_id='.$rId
			);
			
			
			//TODO ProjectTimeEntry related to commit
			/*
			UserHistory::QInsertSelect()->cols('user_id,type,created,rel_id')
				->query(RepositoryRevision::QAll()->setFields(array('(rc.user_id)',AHistory::REPOSITORY_NEW_REVISION,'committed','id'))
					->with('RepositoryCommitter',false)
					->where(array('rc.user_id'=>true))
					->groupBy(array('rc.user_id','rr.id'))
				);
			UserHistory::QInsertSelect()->cols('user_id,type,created,rel_id')
				->query(RepositoryRevision::QAll()->setFields(array('(rc.user_id)',AHistory::ISSUE_CLOSE_BY_REV,'committed','(ir.id)'))
					->with('RepositoryCommitter',false)
					->with('IssueRevision',array('fields'=>false,'join'=>true))
					->where(array('rc.user_id'=>true,'ir.closed'=>true))
					->groupBy(array('rc.user_id','rr.id'))
				);*/
		}
		$committers=RepositoryCommitter::findAllByRepository_id($project->repository_id);
		$users=User::findListName();
		mset($committers,$users);
		render();
	}
	
	/** @ValidParams */
	function revisions(){
		$project=AController::findProject('RepositoryView');
		if(!$project->hasRepository()) notFound();
		$revisions=RepositoryRevision::QAll()->byRepository_id($project->repository_id)
			->orderBy(array('committed'=>'DESC'))->with('RepositoryCommitter','name')
			->paginate()->pageSize(25);
		mset($revisions);
		render();
	}
	
	/** @ValidParams @NotEmpty('path') */
	function revision($path){
		$rev=&$path;
		$project=AController::findProject('RepositoryView');
		if(!$project->hasRepository()) notFound();
		if(!($rev=RepositoryRevision::findOneInfos($project->repository_id,$rev))) notFound();
		$rev->findWith('RepositoryRevisionChange',array('limit'=>1000,'orderBy'=>array('CONCAT(dirname,"z")','filename')));/*force the filename to always be under the folders*/
		mset($rev);
		render();
	}
	
	
	/** @ValidParams */
	function stats(){
		// Commits per month
		// Commits per author
		render();
	}
}
