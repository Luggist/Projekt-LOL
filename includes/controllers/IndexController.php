<?php


class IndexController extends Controller
{
	protected $viewFileName = "index"; //this will be the View that gets the data...
	protected $loginRequired = false;


	public function run()
	{
		$this->view->title = 'LOL Stats';
		$this->view->api = new ExternAPI('RGAPI-6cb84988-ad78-4a15-9275-308870e4a81a');
	}

}