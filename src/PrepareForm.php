<?php

namespace ProjectSoft;

class PrepareForm {

	public static function prepareProcessCallme($modx, $data, $fl, $name)
	{
		$cfg = $fl->config->getConfig();
		$site = $modx->config['site_name'];
		$theme = $fl->getField("formid");
		$theme_val = "Заказ звонка";
		switch($theme){
			case "zamer":
				$theme_val = "Вызов замерщика";
				break;
			case "callme":
				$theme_val = "Заказ звонка";
				break;
		}
		$fl->mailConfig['subject']  = $cfg["subject"] = mb_strtoupper($theme_val, $modx->config['modx_charset']) . " с сайта «" . $site . "»";
		$fl->mailConfig['replyTo']  = $cfg["replyTo"] = $modx->config['email_bot'];
		$fl->mailConfig['fromName']  = $cfg["fromName"] = $modx->config['email_bot_name'];
		$fl->config->setConfig($cfg);
	}

	public static function prepareCallme($modx, $data, $fl, $name)
	{
		$https_port = 443;
		$id = $modx->documentIdentifier;
		$url = $modx->makeUrl($id, '', '');
		$secured = (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https');
		$port = ((isset ($_SERVER['HTTPS']) && ( (strtolower($_SERVER['HTTPS']) == 'on') || ($_SERVER['HTTPS']) == '1')) || $_SERVER['SERVER_PORT'] == $https_port || $secured) ? 'https://' : 'http://';
		$input = $_SERVER['HTTP_HOST'];
		$idna = new idna_convert();
		$host = $port . $idna->decode($input) . $url;
		$theme = $fl->getField("formid");
		$theme_val = "Заказ звонка";
		switch($theme){
			case "zamer":
				$theme_val = "Вызов замерщика";
				break;
			case "callme":
				$theme_val = "Заказ звонка";
				break;
		}
		$fl->setField("pagetitle", $modx->documentObject["pagetitle"]);
		$fl->setField("url", $host);
		$fl->setField("theme", $theme_val);
	}

	public static function prepareAfterProcessCallme($modx, $data, $fl, $name)
	{
		$arr = array(
			"types" => array(
				'date'		=>'Дата',
				'theme'		=>'Тема',
				'name'		=>'Имя',
				'phone'		=>'Телефон',
				'message'	=>'Страница отправки'
			),
			'fields' => array(
				'date'		=> date('d.m.Y H:i:s', time() + $modx->config['server_offset_time']),
				'theme'		=> $fl->getField('theme'),
				'name'		=> $fl->getField('first_name'),
				'phone'		=> $fl->getField('phone'),
				'message'	=> $modx->documentObject["pagetitle"] . " _" . $fl->getField('url') . "_"
			),
		);
		$modx->invokeEvent('onSendBot', $arr);
	}
}