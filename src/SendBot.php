<?php
namespace ProjectSoft;

class SendBot {

	private $modx;

	public function __construct($params)
	{
		$this->modx = EvolutionCMS();
	}

	private function setFields($arr)
	{
		
	}

	public function send($url){
		$ch = curl_init();
		$optArray = array(
				CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => true
		);
		curl_setopt_array($ch, $optArray);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}
}