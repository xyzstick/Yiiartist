<?php
/**
 * LoginForm is the data structure for keeping user login form data. 
 * It is used by the 'login' action of 'SiteController'.
 */
class LoginForm extends CFormModel
{
	/**
	 * Varables Defined
	 */
	public $username;
	public $password;
	public $rememberMe;
	private $_identity;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('username, password','required'),
			// rememberMe needs to be a boolean
			array('rememberMe','boolean'),
			// password needs to be authenticated
			array('password','authenticate'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'rememberMe'=>'Remember me next time',
			'username'=>'Username Or Email',
			'password'=>'Password',
		);
	}

	/**
	 * Authenticates password.
	 * @return boolean whether login is successful
	 * This is the 'authenticate' validator as declared in rules().
	 *
	 * Here we are creating a new UserIdentity object and passing in the authentication 
	 * credentials (i.e. the $username and $password values submitted by the user) to 
	 * its constructor. We then simply call the authenticate() method. If successful, 
	 * we pass the identity information into the CWebUser::login method, which will store
	 * the identity information into persistent storage (PHP session by default) for 
	 * retrieval upon subsequent requests. If the authentication fails, we can interrogate
	 * the errorMessage property for more information as to why it failed.
	 * 
	 * http://www.yiiframework.com/doc/guide/1.1/en/topics.auth#cookie-based-login
	 * CUserIdentity::authenticate(): this is where the real authentication is performed. 
	 * If the user is authenticated, we should re-generate a new random key, and store it in 
	 * the database as well as in the identity states via CBaseUserIdentity::setState.
	 */
	/*public function authenticate($attribute,$params) {
		if(!$this->hasErrors()) {
			$this->_identity=new UserIdentity($this->username,$this->password);
			if(!$this->_identity->authenticate())
				$this->addError('password','Incorrect username or password.');
		}
	}//*/
	public function authenticate($attribute,$params)
	{
		if(!$this->hasErrors())
		{
			if(null ===$this->_identity)
			{
				$this->_identity=new UserIdentity($this->username,$this->password);
				$this->_identity->authenticate();
			}
			switch($this->_identity->errorCode)
			{
				case UserIdentity::ERROR_NONE:
					//$duration=$this->rememberMe?3600*24*2:0;// 2 days
					//Yii::app()->user->login($this->_identity,$duration);
					break;
				case UserIdentity::ERROR_PASSWORD_INVALID:
					$this->addError('password','Password is incorrect.');
					break;
				case UserIdentity::ERROR_USERNAME_INVALID:
					$this->addError('username','Incorrect username or email.');
					break;
				case UserIdentity::ERROR_EMAIL_INVALID:
					$this->addError('username','Incorrect username or email.');
					break;
				case UserIdentity::ERROR_STATUS_INACTIVE:
					$this->addError('status','Your account is being activated, please wait.');
					break;
				case UserIdentity::ERROR_STATUS_BANNED:
					$this->addError('status','You have been banned, your IP address has been logged.');
					break;
				case UserIdentity::ERROR_STATUS_REMOVED:
					$this->addError('status','You have deleted your account, please make another.');
					break;
			}
		}
	}//*/

	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function login()
	{
		if(null === $this->_identity)
		{
			$this->_identity=new UserIdentity($this->username,$this->password);
			$this->_identity->authenticate();
		}
		if($this->_identity->errorCode===UserIdentity::ERROR_NONE)
		{
			$duration=$this->rememberMe?3600*24*2:0;// 2 days
			Yii::app()->user->login($this->_identity,$duration);
			return true;
		} else 
		{
			return false;
		}
	}
}