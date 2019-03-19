<?php

/**
 * Class User
 * Provides methods to login and logout - or to check if someone is loggedIn
 */
class User extends Database
{
	public $username = '';
	public $id = '';

	public $isLoggedIn = false;

	/**
	 * User constructor.
	 * This is a little crazy - especailly the "fillIt" and "shipIt" part.
	 * Instead of just saving a normal value like an integer or a string
	 * one is able to save complex structures by serializing them and store them as a string
	 * with that method - we are able to save public attributes in the session
	 * if there are values in the session we fill our object with those values
	 * not magic - but a little complex on the first sight
	 */
	public function __construct()
	{
		parent::__construct();

		if($_SESSION[get_class($this).'Ship'] != '')
		{
			$ship = $_SESSION[get_class($this)."Ship"];
			$this->fillIt($ship);
		}
	}

	/**
	 * save our values in the session
	 */
	public function __destruct()
	{
		parent::__destruct();

		$_SESSION[get_class($this).'Ship'] = $this->shipIt();
	}

	/**
	 * Checks if the User is logged in - if not redirect him to the login page
	 * @return bool
	 */
	public function authenticate()
	{
		//checks if the user is logged in - if not - redirect to login!
		if(!$this->isLoggedIn)
		{
			define('LOGGED_IN', false);

			$this->redirectToLogin();

			//return false;
		}

		define('LOGGED_IN', true);

		return true;
	}

	public function redirectToLogin()
	{
		if(API_CALL === true)
		{
			header('Location: ../'.LOGIN_URL);
		}
		else
		{
			header('Location: '.LOGIN_URL);
		}
		header('Status: 303');
		exit();
	}

	public function redirectToIndex()
	{
		header('Location: '.INDEX_URL);
		header('Status: 303');
		exit();
	}

	public function login($username, $password)
	{
		$sql = "SELECT `id`,`password` FROM `user` WHERE `name`='" . $this->escapeString($username) . "'";
		$result = $this->query($sql);


		if($this->numRows($result) == 0)
		{
			$this->isLoggedIn = false;
			return false; //username not found!
		}

		//now lets check for the password
		$row = $this->fetchObject($result);

		if(password_verify($password, $row->password))
		{
			$this->username = $username;
			$this->id = $row->id;
			$this->isLoggedIn = true;

			return true;
		}

		$this->isLoggedIn = false;
		return false;
	}

	public static function getById($id)
	{
		$id = intval($id);
		$sql = "SELECT * FROM `user` WHERE `id`=".$id;

		$db = new Database();
		$result = $db->query($sql);

		if($db->numRows($result) > 0)
		{
			//get the data
			$data = $db->fetchObject($result);
			$user = new User();

			$user->username = $data['username'];
			$user->id = $id;

			return $user;
		}

		return null;
	}

	public function logout()
	{
		$this->username = null;
		$this->id = null;
		$this->isLoggedIn = false;
		$this->shipIt();

		//$this->redirectToLogin();

		return true;
	}

	/**
	 * Gets all attributes from this class, serializes it adds slahes to save this string in the session
	 * @return string
	 */
	protected function shipIt()
	{
		$ship = serialize($this);
		$ship = addslashes($ship);
		return $ship;
	}

	/**
	 * Fills this class with the data from the session which was previously saved
	 * @param $ship
	 */
	protected function fillIt($ship)
	{
		$ship = stripslashes($ship);
		$thiz = unserialize($ship);
		$ro = new reflectionObject($thiz);
		foreach ($ro->getProperties() as $propObj)
		{
			$this->{$propObj->name} = $thiz->{$propObj->name};
		}
	}

	public static function existsWithUsername($username)
	{
		$db = new Database();

		//check if user exists...
		$sql = "SELECT COUNT(`id`) AS num FROM `user` WHERE `name`='".$db->escapeString($username)."'";
		$result = $db->query($sql);

		$row = $db->fetchObject($result);

		if($row->num == 0)
		{
			return false;
		}

		return true;
	}

	public static function createUser($data)
	{
		$db = new Database();

		$username = $db->escapeString($data['username']);
		$password = password_hash($db->escapeString($data['password']), PASSWORD_BCRYPT);

		$sql = "INSERT INTO `user`(`name`,`password`) VALUES('".$username."','".$password."')";
		$db->query($sql);
	}

	public static function deleteUser($id)
	{
		//@TODO
	}

	public static function updateUser($data)
	{
		//@TODO
	}

	public function delete()
	{
		self::deleteUser($this->id);
	}

	public function update($data)
	{
		self::updateUser($this->id, $data);
	}
}
