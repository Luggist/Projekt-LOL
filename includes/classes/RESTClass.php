<?php

abstract class RESTClass
{
	abstract protected function getRequest($data);
	abstract protected function createRequest($data);
	abstract protected function saveRequest($data);
	abstract protected function deleteRequest($data);

	public function processRequest($method, $data)
	{

		switch($method)
		{
			case 'post':
				$this->createRequest($data);
				break;

			case 'put':
				$this->saveRequest($data);
				break;

			case 'delete':
				$this->deleteRequest($data);
				break;

			case 'get':
			default:
				$this->getRequest($data);
				break;
		}
	}


	public static function returnJSON($result=False, $setMessage='API call failed!', $data=array(), $sendToken=False)
	{
		$jsonResponse = new JSON();
		$jsonResponse->result = $result;
		$jsonResponse->setMessage($setMessage);
		$jsonResponse->setData($data);
		if ($sendToken) $jsonResponse->setRequestToken();
		$jsonResponse->send();
	}
}