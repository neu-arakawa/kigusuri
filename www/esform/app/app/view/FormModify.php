<?php

require_once 'lib/FormBuilder.php';

class Esform_View_FormModify extends Esform_ViewClass
{
	function preforward()
	{
		$custom_names = array(
			'' => 'なし',
			'checkHiragana'      => 'ふりがな',
			'checkKatakana'      => 'フリガナ',
			'checkMailaddress'   => 'メールアドレス',
			'checkMailaddress_r' => 'メールアドレス ( 控えを送信 )',
			'checkRepeat'        => '上段のフォーム内容の再入力',
			'checkURL'           => 'URL ( http:// )',
			'checkAlphabet'      => '英字 ( abcd )',
			'checkNumber'        => '数字 ( 0123 )',
			'checkAlphanum'      => '英数字 ( 1z3Y )',
			'checkInteger'       => '正整数 ( 1234 )',
			'checkZipcode'       => '郵便番号 ( 000-1111 )',
			'checkZipcode_d'     => '郵便番号 ( 0001111 )',
			'checkTelnum'        => '電話番号 ( 03-111-2222 )',
			'checkTelnum_d'      => '電話番号 ( 031112222 )',
			'checkMobilenum'     => '携帯番号 ( 090-1111-2222 )',
			'checkMobilenum_d'   => '携帯番号 ( 09011112222 )'
		);
		$this->af->setApp('custom_options_values', array_keys($custom_names));
		$this->af->setApp('custom_options_output', $custom_names);
		$this->af->setApp('radios', array(1 => 'はい', 0 => 'いいえ'));

		$params = $this->af->getArray(false);
		$this->af->setApp('params', $params);

		if ($this->af->get('preview')) {
			$builder = new FormBuilder();
			$params['id'] = 'none';
			$values = &$params['values'];
			$values = preg_replace('/\r\n?|\n/', ',', $values);
			$type_name = $params['type_name'];
			$form = $builder->$type_name($params);
			$form = preg_replace('/\<\?.+?\?\>/', '', $form);
			$this->af->setAppNE('form', $form);
		}
	}
}

?>
