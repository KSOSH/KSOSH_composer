<?php

namespace ProjectSoft;

class PrepareForm {

	public static function prpareProcessCallme($modx, $data, $fl, $name)
	{

	}

	public static function prepareCallme($modx, $data, $fl, $name)
	{
		$id = $modx->documentIdentifier;
		$url = $modx->makeUrl($id, '', '', 'full');
		$fl->setField("url", $url);
		$fl->setField("theme", "Заказать звонок");
		switch ($name) {
			case 'prepare':
				// code...
				break;

			default:
				// code...
				break;
		}
	}
}
