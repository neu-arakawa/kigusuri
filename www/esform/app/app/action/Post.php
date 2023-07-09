<?php

class Esform_Form_Post extends Esform_ActionForm
{
	var $use_validator_plugin = true;

	var $form = array(
			'id' => array(
				'type'         => VAR_TYPE_STRING,
				'name'         => 'ファイル番号',
				'required'     => true,
				'regexp'       => '/^[1-9]\d*$/',
			),
			'_send' => array(
			),
	);

	function checkRepeat($name)
	{
		$string = &$this->form_vars[$name];
		if ($string == '') return;
		$pre_string = null;
		foreach ($this->form_vars as $key => $val) {
			if ($key === $name) break;
			$pre_string = $val;
		}

		if ($string !== $pre_string) {
			$this->ae->add($name, '{form}を正しく入力して下さい', E_FORM_INVALIDCHAR);
		}
	}
	function checkMailaddress($name)
	{
		$string = &$this->form_vars[$name];
		if ($string == '') return;
		$string = mb_convert_kana($string, 'as');

		if (!Ethna_Util::checkMailaddress($string)) {
			$this->ae->add($name, '{form}を正しく入力して下さい', E_FORM_INVALIDCHAR);
		}
	}
	function checkMailaddress_r($name)
	{
		$this->checkMailaddress($name);
	}
	function checkURL($name)
	{
		$string = &$this->form_vars[$name];
		if ($string == '') return;
		$string = mb_convert_kana($string, 'as');

		if (!preg_match('{^(https?|ftp)://.+}', $string)) {
			$this->ae->add($name, '{form}を正しく入力して下さい', E_FORM_INVALIDCHAR);
		}
	}
	function checkAlphabet($name)
	{
		$string = &$this->form_vars[$name];
		if ($string == '') return;
		$string = mb_convert_kana($string, 'as');

		if (!preg_match('/^[a-z]+$/i', $string)) {
			$this->ae->add($name, '{form}は英字にして下さい', E_FORM_INVALIDCHAR);
		}
	}
	function checkNumber($name)
	{
		$string = &$this->form_vars[$name];
		if ($string == '') return;
		$string = mb_convert_kana($string, 'as');

		if (!preg_match('/^\d+$/', $string)) {
			$this->ae->add($name, '{form}は数字にして下さい', E_FORM_INVALIDCHAR);
		}
	}
	function checkAlphanum($name)
	{
		$string = &$this->form_vars[$name];
		if ($string == '') return;
		$string = mb_convert_kana($string, 'as');

		if (!preg_match('/^[a-z\d]+$/i', $string)) {
			$this->ae->add($name, '{form}は英数字にして下さい', E_FORM_INVALIDCHAR);
		}
	}
	function checkInteger($name)
	{
		$string = &$this->form_vars[$name];
		if ($string == '') return;
		$string = mb_convert_kana($string, 'as');

		if (!preg_match('/^[1-9]\d*$/', $string)) {
			$this->ae->add($name, '{form}は正整数にして下さい', E_FORM_INVALIDCHAR);
		}
	}
	function checkZipcode($name)
	{
		$string = &$this->form_vars[$name];
		if ($string == '') return;
		$string = mb_convert_kana($string, 'as');

		if (!preg_match('/^\d{3}-\d{4}$/', $string)) {
			$this->ae->add($name, '{form}を正しく入力して下さい', E_FORM_INVALIDCHAR);
		}
	}
	function checkZipcode_d($name)
	{
		$string = &$this->form_vars[$name];
		if ($string == '') return;
		$string = mb_convert_kana($string, 'as');
		$string = str_replace($string, '-', '');

		if (!preg_match('/^\d{7}$/', $string)) {
			$this->ae->add($name, '{form}を正しく入力して下さい', E_FORM_INVALIDCHAR);
		}
	}
	function checkTelnum($name)
	{
		$string = &$this->form_vars[$name];
		if ($string == '') return;
		$string = mb_convert_kana($string, 'as');

		if (!preg_match('/^0[1-9]\d{0,3}-\d{1,4}-\d{4}$/', $string)) {
			$this->ae->add($name, '{form}を正しく入力して下さい', E_FORM_INVALIDCHAR);
		}
	}
	function checkTelnum_d($name)
	{
		$string = &$this->form_vars[$name];
		if ($string == '') return;
		$string = mb_convert_kana($string, 'as');
		$string = str_replace($string, '-', '');

		if (!preg_match('/^0[1-9]\d{8}$/', $string)) {
			$this->ae->add($name, '{form}を正しく入力して下さい', E_FORM_INVALIDCHAR);
		}
	}
	function checkMobilenum($name)
	{
		$string = &$this->form_vars[$name];
		if ($string == '') return;
		$string = mb_convert_kana($string, 'as');

		if (!preg_match('/^0[7-9]0-\d{4}-\d{4}$/', $string)) {
			$this->ae->add($name, '{form}を正しく入力して下さい', E_FORM_INVALIDCHAR);
		}
	}
	function checkMobilenum_d($name)
	{
		$string = &$this->form_vars[$name];
		if ($string == '') return;
		$string = mb_convert_kana($string, 'as');
		$string = str_replace($string, '-', '');

		if (!preg_match('/^0[7-9]0\d{8}$/', $string)) {
			$this->ae->add($name, '{form}を正しく入力して下さい', E_FORM_INVALIDCHAR);
		}
	}
	function checkKatakana($name)
	{
		$string = &$this->form_vars[$name];
		if ($string == '') return;
		$string = mb_convert_kana($string, 'CKs');

		if (!preg_match('/^[ア-ン ]+$/u', $string)) {
			$this->ae->add($name, '{form}は片仮名で入力して下さい', E_FORM_INVALIDCHAR);
		}
	}
	function checkHiragana($name)
	{
		$string = &$this->form_vars[$name];
		if ($string == '') return;
		$string = mb_convert_kana($string, 'cHs');

		if (!preg_match('/^[あ-ん ]+$/u', $string)) {
			$this->ae->add($name, '{form}は平仮名で入力して下さい', E_FORM_INVALIDCHAR);
		}
	}
}
class Esform_Action_Post extends Esform_ActionClass
{
	var $vars;
	var $attr_list;
	var $is_mobile;

	function prepare()
	{
		$this->vars = array(
			'ERROR'   => false,
			'DONE'    => false,
			'CONFIRM' => false
		);
		$carrier = $this->getCarrier();
		$this->is_mobile = (bool)$carrier;

		if (empty($_POST)) {
			return null;
		}
		if ($this->af->validate() > 0) {
			return null;
		}

		$id = $this->af->get('id');
		$data_file = sprintf('%s%03d.cgi', C_DATA_DIR, $id);
		if (!is_file($data_file)) {
			return null;
		}

		$data = &$this->backend->getManager('Data');
		$data->load($data_file);

		$array = $data->getArray();
		$this->attr_list = $array['attr'];

		// 入力チェック
		if ($this->validate()) {
			$this->setErrorVars();
			return null;
		}

		// 確認画面へ
		if ($this->af->get('_send') === null) {
			$this->setConfirmVars();
			return null;
		}

		// 連続投稿チェック
		$time = time();
		$host_id = $this->getHostId();
		$check_file = './app/tmp/' . $host_id;
		if (is_file($check_file)) {
			$expire = filemtime($check_file) + C_REPOST_INTERVAL;
			$remain = $expire - $time;
			if ($remain > 0) {
				$this->vars['ERROR'] = 'あと' . $remain . '秒お待ち下さい';
				return null;
			}
		}

		// メール送信
		$attach_list = array();
		$body_vars = array();
		$from = '';
		$attr_list = $this->attr_list;
		foreach ($attr_list as $attr) {
			extract($attr);
			$value = $this->af->get($id);

			if (is_array($value)) {
				$value = implode(', ', $value);
			}

			if ($custom == 'checkMailaddress') {
				$from = $from == '' ? $value : $from;
				$value = 'mailto:' . $value;
			} else if ($custom == 'checkMailaddress_r') {
				$receipt_to = $value;
				$from = $from == '' ? $value : $from;
				$value = 'mailto:' . $value;
			} else if ($type_name == 'FILE' && $value != null) {
				$file = preg_replace('/^.+_/', '', $value);
				$file = './app/tmp/' . $file;
				if (!is_file($file)) {
					$this->vars['ERROR'] = $file . 'がアップロードされていません';
					return null;
				}
				$value = preg_replace('/_[^_]+$/', '', $value);
				$type = $this->af->get($id . '_type');
				$attach_list[] = array($value, $type, file_get_contents($file));
			}

			$this->changeBlankText($value, $type_name);
			$body_vars["{\$$id}"] = $value;
		}
		$serial = Ethna_Util::getRandom(12);
		$ip = $_SERVER['REMOTE_ADDR'];
		$host = getHostByAddr($ip);
		$body_vars['{$ip}'] = $ip;
		$body_vars['{$host}'] = $host;
		$body_vars['{$browser}'] = $_SERVER['HTTP_USER_AGENT'];
		$body_vars['{$referer}'] = $_SERVER['HTTP_REFERER'];
		$body_vars['{$carrier}'] = $this->getCarrierName($carrier);
		$body_vars['{$date}'] = date('y年m月d日 H時i分', $time);
		$body_vars['{$serial}'] = strToUpper($serial);

		$subject = $data->get('name');
		$body = $data->get('body');
		$body = str_replace(',', "\n", $body) . "\n";
		$body = strtr($body, $body_vars);
		$receipt = $data->get('receipt');
		$receipt = str_replace(',', "\n", $receipt) . "\n";
		$receipt = strtr($receipt, $body_vars);

		$header_list = array();
		if ((bool)$attach_list) {
			$boundary = '----=_NextPart_' . $serial;
			$header_list[] = sprintf('Content-Type: multipart/mixed;');
			$header_list[] = sprintf('	boundary="%s"', $boundary);
			$body = <<< EOM
--$boundary
Content-Type: text/plain; charset=ISO-2022-JP
Content-Transfer-Encoding: 7bit

$body
EOM;
			foreach ($attach_list as $attach) {
				$attach_name = h($attach[0]);
				$attach_type = $attach[1];
				$attach_data = chunk_split(base64_encode($attach[2]));
				$body .= <<< EOM


--$boundary
Content-Type: $attach_type;
	name="$attach_name"
Content-Transfer-Encoding: base64
Content-Disposition: attachment;
	filename="$attach_name"

$attach_data
EOM;
			}
			$body .= <<< EOM

--$boundary--


.

EOM;
		}

		if (strlen(C_ADMIN_MAIL) > 5) {
			$success = $this->sendMail(C_ADMIN_MAIL, $subject, '', $body, $from, $header_list);
			if (!$success) {
				$this->vars['ERROR'] = 'メールを送信できませんでした';
				return null;
			}
		}
		if (isset($receipt_to)) {
			$subject .= ' [送信者控え]';
			$success = $this->sendMail($receipt_to, $subject, '', $receipt, $from);
		}

		if (is_writable(C_LOG_DIR)) {
			// 送信ログ作成
			$id = $this->af->get('id');
			$log_file = sprintf('%s%03d_%s_%s.cgi', C_LOG_DIR, $id, $time, $host_id);
			$fp = fopen($log_file, 'w+b');
			fwrite($fp, $body);
			fclose($fp);

			// アンケート集計
			$anquete_file = sprintf('%s%03d_enquete.cgi', C_LOG_DIR, $id);
			if (is_file($anquete_file)) {
				$code = file_get_contents($anquete_file);
				$array = unserialize($code);
			} else {
				$array = array(
					'host_list' => array(),
					'item_list' => array()
				);
			}
			$host_list = &$array['host_list'];
			$item_list = &$array['item_list'];
			if (!in_array($host, $host_list) && $this->setEnquete($item_list)) {
				$array['name'] = $data->get('name');
				$host_list[] = $host;
				$fp = fopen($anquete_file, 'w+b');
				fwrite($fp, serialize($array));
				fclose($fp);
			}
		}

		$this->gc();
		touch($check_file);
		$this->vars['DONE'] = true;
	}
	function perform()
	{
		if (C_AUTO_SELECT) {
			$form_file = $_SERVER['SCRIPT_NAME'];
			$form_file = basename($form_file, '.php');
			$form_file = basename($form_file, C_MOBILE_SUFFIX);
			$form_file_p = C_PUBLISH_DIR . $form_file . '.php';
			$form_file_m = C_PUBLISH_DIR . $form_file . C_MOBILE_SUFFIX . '.php';
			if ($this->is_mobile) {
				$form_file = is_file($form_file_m) ? $form_file_m : $form_file_p;
			} else {
				$form_file = is_file($form_file_p) ? $form_file_p : $form_file_m;
			}
		} else {
			$form_file = C_PUBLISH_DIR . basename($_SERVER['SCRIPT_NAME']);
		}

		// 入力エラーの一覧
		$errors = array();
		$vars = &$this->vars;
		foreach ($vars as $key => $val) {
			if (substr($key, -2) == '_e' && $val != '') {
				$errors[$key] = $val;
			}
		}

		// 簡易カウント用
		$i = 0;
		error_reporting(6135);
		extract($this->vars);
		include $form_file;
		exit;
	}
	function getCarrier()
	{
		$agent = $_SERVER['HTTP_USER_AGENT'];
		if (preg_match('{^DoCoMo/[12]\.0}', $agent)) {
			return 'd';
		} else if (preg_match('{^(J-PHONE|Vodafone|MOT-[CV]980|SoftBank)/}', $agent)) {
			return 's';
		} else if (strpos($agent, 'KDDI-') === 0 || strpos($agent, 'UP.Browser') !== false) {
			return 'a';
		} else if (strpos($agent, 'PDXGW') === 0 || strpos($agent, 'DDIPOCKET;') !== false || strpos($agent, 'WILLCOM;') !== false) {
			return 'w';
		} else {
			return null;
		}
	}
	function getCarrierName($carrier)
	{
		switch ($carrier) {
			case 'd':
				return 'ドコモ端末';
			case 's':
				return 'ソフトバンク端末';
			case 'a':
				return 'AU端末';
			case 'w':
				return 'ウィルコム端末';
			default:
				return 'パソコン';
		}
	}
	function gc()
	{
		$dir = './app/tmp/';
		$list = array();
		$dp = opendir($dir);
		while (($file = readdir($dp)) !== false) {
			if (preg_match('/^[a-z\d]+(\.[a-z]{2,4})?$/i', $file)) {
				$list[] = $file;
			}
		}
		closedir($dp);

		$lifetime = 86400;
		$files = 0;
		$time = time();
		foreach ($list as $file) {
			$file = $dir . $file;
			$expire = filemtime($file) + $lifetime;
			$time > $expire ? unlink($file) : ++$files;
		}

		return $files;
	}
	function getHostId($length = 15)
	{
		$string = getHostByAddr($_SERVER['REMOTE_ADDR']);
		$string = md5($string);
		$string = substr($string, -$length);
		return $string;
	}
	function nlToBr($string)
	{
		$string = preg_replace('/\r\n?|\n/', '<br />', $string);
		return $string;
	}
	function sendMail($to, $subject, $name, $body, $from = '', $header_list = null, $option = null)
	{
		$host = $_SERVER['HTTP_HOST'];
		if ($from === '') {
			$from = 'no-accounts@' . $host;
		}
		if ($name !== '') {
			$name = mb_convert_encoding($name, 'JIS');
			$name = '=?ISO-2022-JP?B?' . base64_encode($name) . '?=';
			$from = "$name <$from>";
		}

		$subject = mb_convert_encoding($subject, 'JIS');
		$subject = '=?ISO-2022-JP?B?' . base64_encode($subject) . '?=';
		$body = preg_replace('/\r\n?/', "\n", $body);
		$body = mb_convert_encoding($body, 'JIS');

		$list = array(
			'From: ' . $from,
			'MIME-Version: 1.0',
			'X-Mailer: PHP ' . PHP_VERSION,
			'Content-Type: text/plain; charset=ISO-2022-JP',
			'Content-Transfer-Encoding: 7bit'
		);
		if ((bool)$header_list) {
			array_splice($list, -2, 2, $header_list);
		}
		$header = implode("\n", $list);

		// テスト環境
		if ($host === 'localhost') {
			return true;
		} else if ($option === null) {
			return mail($to, $subject, $body, $header);
		} else {
			return mail($to, $subject, $body, $header, $option);
		}
	}
	function validate()
	{
		// 設定ファイルから検証ルールを作成
		$attr_list = $this->attr_list;
		foreach ($attr_list as $attr) {
			extract($attr);

			$rule = array(
				'name'         => $name,
				'type'         => $type,
				'form_type'    => $form_type,
				'filter'       => 'ntrim,trim,gpc',
				'required'     => $required,
				'min'          => $min,
				'max'          => $max
			);
			if ($regexp != '') {
				$rule['regexp'] = $regexp;
				$rule['regexp_error'] = $regexp_error;
			}
			if ($custom != '') {
				$rule['custom'] = $custom;
			}

			if ($type_name == 'FILE') {
				if ($this->af->get('_send') == null) {
					$rule['extension'] = true;
					$rule['filter'] = null;
				} else {
					$rule['type'] = VAR_TYPE_STRING;
					$rule['form_type'] = FORM_TYPE_TEXT;
					$rule['max'] = 100;
					$rule['regexp'] = '/^.+_[a-z\d]+\.[a-z]{2,4}$/i';
					$rule['regexp_error'] = '{form}を正しく入力して下さい';

					$type_rule = array(
						'name'         => $name . 'のファイルタイプ',
						'required'     => $required,
						'type'         => VAR_TYPE_STRING,
						'form_type'    => FORM_TYPE_HIDDEN,
						'min'          => 0,
						'max'          => 100,
						'regexp'       => '/^[a-z\-\/]+$/',
						'regexp_error' => '{form}を正しく入力して下さい'
					);
					$this->af->setDef($id . '_type', $type_rule);
				}
			}

			$this->af->setDef($id, $rule);
		}
		$this->af->setFormVars();
		return $this->af->validate();
	}
	function setErrorVars()
	{
		$attr_list = $this->attr_list;
		foreach ($attr_list as $attr) {
			extract($attr);

			// エラー文を作成
			if ($this->ae->isError($id)) {
				$msg = $this->ae->getMessage($id) . '。';
				$this->vars[$id . '_e'] = h($msg);
			} else {
				$this->vars[$id . '_e'] = '';
			}

			// 入力内容を復元
			$value = $this->af->get($id);
			if ($type_name == 'CHECKBOX') {
				$value = $value == null ? array() : array_flip($value);
				$values = explode(',', $values);
				foreach ($values as $i => $v) {
					$key = $id . $i . '_v';
					if (isset($value[$v])) {
						$this->vars[$key] = ' checked="checked"';
					} else {
						$this->vars[$key] = '';
					}
				}
			} else if ($type_name == 'RADIO' || $type_name == 'SELECT') {
				$values = explode(',', $values);
				foreach ($values as $i => $v) {
					$key = $id . $i . '_v';
					if ($value == $v) {
						$this->vars[$key] = $type_name == 'RADIO' ? ' checked="checked"' : ' selected="selected"';
					} else {
						$this->vars[$key] = '';
					}
				}
			} else if ($type_name == 'FILE') {
			} else {
				$this->vars[$id . '_v'] = h($value);
			}
		}
	}
	function setConfirmVars()
	{
		// 送信内容をhiddenで復元
		$this->vars['hidden'] = '';
		$hidden = &$this->vars['hidden'];
		$attr_list = $this->attr_list;
		foreach ($attr_list as $attr) {
			extract($attr);
			$value = $this->af->get($id);
			$name = $type_name == 'CHECKBOX' ? $id . '[]' : $id;

			$this->vars[$id . '_c'] = '';
			$display = &$this->vars[$id . '_c'];
			if ($type_name == 'FILE') {
				if (!empty($value['size'])) {
					$file_name = $display = basename($value['name']);
					$dst_name = md5($id . $this->getHostId() . time());
					$dst_name = substr($dst_name, -15);
					$dst_name = preg_replace('/^.+(?=\.)/', $dst_name, $file_name);
					$src = $value['tmp_name'];
					$dst = './app/tmp/' . $dst_name;
					if (!move_uploaded_file($src, $dst)) {
						$this->vars['ERROR'] = 'アップロードに失敗しました';
						return null;
					}
					$hidden = $this->getHidden($name . '_type', $value['type']);
					$hidden = $this->getHidden($name, sprintf('%s_%s', $file_name, $dst_name));
				}
			} else if ($type_name == 'CHECKBOX' && $value !== null) {
				foreach ($value as $v) {
					$hidden = $this->getHidden($name, $v);
				}
				$display = implode(', ', $value);
			} else {
				$hidden = $this->getHidden($name, $value);
				$display = $value;
			}
			$this->changeBlankText($display, $type_name);
			$display = h($display);
			$display = $this->nlToBr($display);
		}
		$hidden .= "\n";
		$this->vars['CONFIRM'] = true;
	}
	function changeBlankText(&$value, $type_name)
	{
		if ($value == '') {
			$value = $type_name[0] == 'T' ? '[入力なし]' : '[選択なし]';
		}
	}
	function getHidden($name, $value)
	{
		static $line = '';
		return $line .= sprintf('<input type="hidden" name="%s" value="%s" />', $name, h($value));
	}
	function setEnquete(&$array)
	{
		$attr_list = $this->attr_list;
		foreach ($attr_list as $attr) {
			extract($attr);
			$tn = $type_name[0];
			if ($tn != 'S' && $tn != 'R' && $tn != 'C') {
				continue;
			}

			if (empty($array[$id])) {
				$types = array(
					'S' => 'セレクトボックス',
					'R' => 'ラジオボタン',
					'C' => 'チェックボックス'
				);
				$array[$id] = array(
					'name' => '',
					'type' => $types[$tn],
					'vote' => array()
				);
			}
			$arr = &$array[$id];
			$arr['name'] = $name;
			$vote = &$arr['vote'];

			$value = $this->af->get($id);
			$values = h($values);
			$values = explode(',', $values);
			foreach ($values as $val) {
				// 改行用の空値、「選択して下さい」、グループ名、集計結果の選択肢に記録済み
				$tmp = $val[0];
				if ($val == '' || ($tn == 'S' && ($tmp == '-' || $tmp == '+')) || isset($vote[$val])) {
					continue;
				}
				$vote[$val] = 0;
			}

			$val_list = array();
			if ($tn == 'C') {
				if (current($value) != null) {
					$val_list = $value;
				}
			} else if ($value != null) {
				$val_list = array($value);
			}
			foreach ($val_list as $val) {
				if (isset($vote[$val])) {
					++$vote[$val];
				}
			}
		}
		return isset($value);
	}
}

?>
