<?php


class DashboardController extends Controller
{
	protected $viewFileName = "dashboard"; //this will be the View that gets the data...
	protected $loginRequired = true;


	public function run()
	{
		$this->view->title = 'Dashboard';
	}
}