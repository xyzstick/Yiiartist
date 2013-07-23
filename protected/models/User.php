<?php
/**
 * This is the model class for table "{{User}}".
 *
 * The followings are the available columns in table '{{User}}':
 * @property integer $id
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string $ipaddress
 * @property integer $status
 * @property string $profile
 * @property string $created
 * @property string $updated
 * @property string $last_login
 * @property integer $lock_account
 */
class User extends CActiveRecord
{
	/**
	 * Varables Constants Defined
	 */
	const STATUS_INACTIVE=0;
	const STATUS_ACTIVE=1;
	const STATUS_BANNED=-1;
	const STATUS_REMOVED=-2;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{User}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username,email,password,created', 'required'),
			array('status', 'numerical', 'integerOnly'=>true),
			array('username,email,password', 'length', 'max'=>128),
			array('ipaddress', 'length', 'max'=>40),
			array('profile,updated,last_login', 'safe'),
			array('status', 'in', 'range'=>array(0,1,-1,-2)),
			//array('status','in','range'=>array(self::STATUS_INACTIVE, self::STATUS_ACTIVE, self::STATUS_BANNED, self::STATUS_REMOVED)),			
			
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			//array('id, username, email, password, ipaddress, status, profile, created, updated, last_login, lock_account','safe','on'=>'search'),
			array('username, email, profile', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array();
	}

	public function scopes()
	{
		return array(
			'active'=>array('condition'=>'status=' . self::STATUS_ACTIVE,),
			'inactive'=>array('condition'=>'status=' . self::STATUS_INACTIVE,),
			'banned'=>array('condition'=>'status=' . self::STATUS_BANNED,),
			'removed'=>array('condition'=>'status=' . self::STATUS_REMOVED,),
		);
	}//*/

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'username'=>'Username',
			'email'=>'Email',
			'password'=>'Password',
			'ipaddress'=>'Ipaddress',
			'status' =>'Status',
			'profile'=>'Profile',
			'created'=>'Created',
			'updated'=>'Updated',
			'last_login'=>'Last Login',
			'lock_account'=>'Lock Account',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.
		$criteria = new CDbCriteria;
		$criteria->compare('id', $this->id);
		$criteria->compare('username', $this->username,true);
		$criteria->compare('email', $this->email,true);
		//$criteria->compare('password', $this->password,true);
		//$criteria->compare('ipaddress', $this->ipaddress,true);
		$criteria->compare('status', $this->status);
		$criteria->compare('profile', $this->profile,true);
		//$criteria->compare('created', $this->created,true);
		//$criteria->compare('updated', $this->updated,true);
		$criteria->compare('last_login', $this->last_login,true);
		$criteria->compare('lock_account', $this->lock_account,true);
		return new CActiveDataProvider($this, array('criteria'=>$criteria,));
	}

	/**
	 * Checks if the given password is correct.
	 * @param string the password to be validated
	 * @return boolean whether the password is valid
	 */
	public function validatePassword($password)
	{
		return crypt($password, $this->password) === $this->password;
	}

	/**
	 * Generates the password hash.
	 * @param string password
	 * @return string hash
	 */
	public function hashPassword($password)
	{
		return crypt($password, $this->generateSalt());
	}

	/**
	 * Generates a salt that can be used to generate a password hash.
	 *
	 * The {@link http://php.net/manual/en/function.crypt.php PHP `crypt()` built-in function}
	 * requires, for the Blowfish hash algorithm, a salt string in a specific format:
	 *  - "$2a$"
	 *  - a two digit cost parameter
	 *  - "$"
	 *  - 22 characters from the alphabet "./0-9A-Za-z".
	 *
	 * @param int cost parameter for Blowfish hash algorithm
	 * @return string the salt
	 */
	protected function generateSalt($cost=10)
	{
		if(!is_numeric($cost) || $cost<4 || $cost>31)
		{
			throw new CException(Yii::t('Cost parameter must be between 4 and 31.'));
		}
		// Get some pseudo-random data from mt_rand().
		$rand = '';
		for($i=0; $i<8; ++$i)
		{
			$rand.=pack('S',mt_rand(0,0xffff));
		}
		// Add the microtime for a little more entropy.
		$rand .= microtime();
		// Mix the bits cryptographically.
		$rand = sha1($rand, true);
		// Form the prefix that specifies hash algorithm type and cost parameter.
		$salt = '$2a$' . str_pad((int) $cost, 2, '0', STR_PAD_RIGHT) . '$';
		// Append the random salt string in the required base64 format.
		$salt .= strtr(substr(base64_encode($rand), 0, 22), array('+' => '.'));
		return $salt;
	}
}