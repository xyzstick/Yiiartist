<?php
/**
 * Identity Class
 * http://www.yiiframework.com/doc/guide/1.1/en/topics.auth#defining-identity-class
 * Authentication is about validating the identity of the user. A typical Web 
 * application authentication implementation usually involves using a username and 
 * password combination to verify a user's identity. However, it may include other 
 * methods and different implementations may be required. To accommodate varying 
 * authentication methods, the Yii auth framework introduces the identity class.
 *
 * We define an identity class which contains the actual authentication logic. The 
 * identity class should implement the IUserIdentity interface. Different identity 
 * classes can be implemented for different authentication approaches (e.g. OpenID, 
 * LDAP, Twitter OAuth, Facebook Connect). A good start when writing your own implementation 
 * is to extend CUserIdentity which is a base class for the authentication approach 
 * using a username and password.
 */
class UserIdentity extends CUserIdentity
{
	/**
	 * Varables Constants Defined
	 */
	const ERROR_EMAIL_INVALID=3;
	const ERROR_STATUS_INACTIVE=4;
	const ERROR_STATUS_BANNED=5;
	private $_id;
	
	/**
	 * Authenticates a user.
	 * @return boolean whether authentication succeeds.
	 * 
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * 
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
		if(strpos($this->username, "@"))
		{
			$user = User::model()->findByAttributes(array('email'=>$this->username));
		}
		else
		{
			$user = User::model()->findByAttributes(array('username'=>$this->username));
		}
		if(null === $user)
		{
			if(strpos($this->username, "@"))
			{
				$this->errorCode=self::ERROR_EMAIL_INVALID;
			}
			else 
			{
				$this->errorCode=self::ERROR_USERNAME_INVALID;
			}
		}
		elseif(!$user->validatePassword($this->password))
		{
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		}
		elseif($user->status == 0)
		{
			$this->errorCode=self::ERROR_STATUS_INACTIVE;
		}
		elseif($user->status == -1)
		{
			$this->errorCode=self::ERROR_STATUS_BANNED;
		}
		else 
		{
			$this->_id = $user->id;
			$this->username = $user->username;
			
			//Start to set the recent_login time for this user
			//$user->last_login = time();
			if(null === $user->last_login)
			{
			    $loginTime = time();
			} 
			else
			{
			    $loginTime = strtotime($user->last_login);
			}
			$this->setState('lastLogin', $loginTime);
			$user->save();

			$this->errorCode = self::ERROR_NONE;
		}
		return $this->errorCode == self::ERROR_NONE;
	}

	/**
	 * @return integer of the ID of the user record
	 * Overriding the CUserIdentity::getId() method to return the _id property 
	 * because the default implementation returns the username as the ID.
	 */
	public function getId()
	{
		return $this->_id;
	}
}