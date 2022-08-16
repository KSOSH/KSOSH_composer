<?php
namespace ProjectSoft;

/**
 * Отправка сообщения на канал Telegram
 * 
	$modx->invokeEvent('onSendBot', array(
		'types' => {
			'date':		'Дата',
			'theme':	'Тема',
			'name':		'Имя',
			'phone':	'Телефон',
			'email':	'Электронная почта',
			'message':	'Сообщение'
			// .....
		},
		'fields' => {
			'date':		'01.01.2023',
			'theme':	'Закакз звонка',
			'name':		'Иван',
			'phone':	'+7(999)999-99-99',
			'email':	'exemple@exemple.com',
			'message':	'Сообщение для вывода на канале'
			// .....
		},
		'before_msg' => 'Вступение сообщения',
		'after_msg' => 'Конец сообщения',
		'bot_token' => 'bot<API:TOKEN>',
		'chat_id' => 'chat_id_Identification',
		'parse_mode' => 'Markdown', // Or HTML 
		'disable_web_page_preview' => 'true' // Or 'false'
	));
**/
class SendBot {

	private const API = 'https://api.telegram.org/bot';

	private $modx;
	private $types;
	private $fields;
	private $before_msg = '';
	private $after_msg = '';
	private $bot_token;
	private $chat_id;
	private $parse_mode = 'Markdown';
	private $disable_web_page_preview = 'true';
	private $msg = '';
	private $url = '';

	public function __construct($params)
	{
		$this->types = is_array($params['types']) ? $params['types'] : array();
		$this->fields = is_array($params['fields']) ? $params['fields'] : array();
		$this->before_msg = is_string($params['before_msg']) ? $params['before_msg'] : "";
		$this->after_msg = is_string($params['after_msg']) ? $params['after_msg'] : "";
		$this->bot_token = $params['bot_token'];
		$this->chat_id = $params['chat_id'];
		$this->disable_web_page_preview = "&disable_web_page_preview=" . (is_string($params['disable_web_page_preview']) ? ($params['disable_web_page_preview'] == "false" ? "false" : "true") : "true");
		$this->msg = $this->setData();
		$this->url = $this->formatUrl();
	}

	private function setData()
	{
		$msg = '';
		$sep = "\n";
		foreach($this->types as $key => $value)
		{
			$val = trim($this->fields[$key]);
			if(mb_strlen($val) > 1)
			{
				$msg .= '*' . $value . ':* ' . $val . "\n";
			}
		}
		$this->before_msg = trim($this->before_msg);
		if(mb_strlen($this->before_msg)>1)
		{
			$this->before_msg .= "\n\n";
		}
		$this->after_msg = trim($this->after_msg);
		if(mb_strlen($this->after_msg)>1)
		{
			$this->after_msg = "\n\n" . $this->after_msg;
		}
		return $this->before_msg . trim($msg) . $this->after_msg;
	}

	private function formatUrl()
	{
		$parse_mode = '&parse_mode=' . $this->parse_mode;
		$disable_web_page_preview = $this->disable_web_page_preview;
		$url = self::API . $this->bot_token . '/sendMessage?chat_id=' . $this->chat_id . '&text=' . urlencode($this->msg) . $parse_mode . $disable_web_page_preview;
		return $url;
	}

	public function send(){
		$url = $this->url;
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