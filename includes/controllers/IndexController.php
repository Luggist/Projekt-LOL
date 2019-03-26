<?php


class IndexController extends Controller
{
	protected $viewFileName = "index"; //this will be the View that gets the data...
	protected $loginRequired = false;


	public function run()
	{
		$this->view->title = 'LOL Stats';
		$this->view->api = new ExternAPI('RGAPI-9a4c9202-d193-4cb5-b41c-5c753b89b547');
	}

}