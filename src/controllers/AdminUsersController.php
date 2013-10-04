<?php
Controller::$defaultLayout='admin';
/** @Check(9) */
class AdminUsersController extends Controller{
	/** */
	static function index(){
		$table=User::Table()->fields('id,first_name,last_name,email,status,created,updated')
			->allowFilters()->exportable('csv,xls','users','Utilisateurs')->paginate();
		
		/*$table=CTable::create(User::QAll()->fields('id,first_name,last_name,email,status,created,updated'));*/
		mset($table);
		render();
	}
	
	/** @Valid('user') */
	static function add(User $user){
		if($user!==null && !CValidation::hasErrors()){
			if($user->pwd !== $user->pwd_confirm) CValidation::addError('pwd',_t('The password is not the same'));
			elseif(User::QExist()->byEmail($user->email)->fetch()) CValidation::addError('email',_t('This email is already registered'));
			else{
				$user->pwd=USecure::hashWithSalt($user->pwd);
				$user->insert();
				CMail::send('user_activation',array('user'=>$user),
					_t('Activation of your account').' - '.CSettings::get('applicationTitle'),$user->email);
				redirect('/adminUsers');
			}
			mset($user);
		}
		render();
	}
}