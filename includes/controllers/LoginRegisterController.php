<?php


class LoginRegisterController extends Controller
{
	protected $viewFileName = "loginRegister"; //this will be the View that gets the data...
	protected $loginRequired = false;


	public function run()
	{
		$this->view->title = 'Login / Register';
	}
}