<?php

require_once 'lib/FormBuilder.php';

class Esform_Form_ProjectTmplPublishDo extends Esform_ActionForm
{
	var $use_validator_plugin = true;

	var $form = array(
			'id' => array(
				'type'         => VAR_TYPE_STRING,
				'name'         => 'ファイル番号',
				'required'     => true,
				'regexp'       => '/^[1-9]\d*$/',
			),
			'index' => array(
				'type'         => VAR_TYPE_STRING,
				'name'         => 'フォーム番号',
				'required'     => false,
				'regexp'       => '/^[1-9]\d*$/',
			),
			'type' => array(
				'form_type'    => FORM_TYPE_RADIO,
				'type'         => VAR_TYPE_STRING,
				'name'         => 'テンプレート・タイプ',
				'required'     => true,
			),
	);
}
class Esform_Action_ProjectTmplPublishDo extends Esform_Action_Project
{
	function prepare()
	{
		if (empty($_POST)) {
			return 'login';
		}
		if ($this->af->validate() > 0) {
			return 'tmplPublish';
		}
	}
	function perform()
	{
		if (!is_writable(C_PUBLISH_DIR)) {
			$this->ae->add('error', C_PUBLISH_DIR . 'に書き込み権限がありません');
			return 'error';
		}
		if (!is_writable(C_DATA_DIR)) {
			$this->ae->add('error', C_DATA_DIR . 'に書き込み権限がありません');
			return 'error';
		}

		$id = $this->af->get('id');
		$data_file = sprintf('%s%03d.cgi', C_DATA_DIR, $id);
		if (!is_file($data_file)) {
			$this->ae->add('error', $data_file . 'が見つかりませんでした');
			return 'error';
		}
		if (!is_writable($data_file)) {
			$this->ae->add('error', $data_file . 'に書き込み権限がありません');
			return 'error';
		}

		$data = &$this->backend->getManager('Data');
		$data->load($data_file);
		$form_file = $data->get('file');
		$form_name = $data->get('name');
		$form_name = h($form_name);
		$array = &$data->getArray();
		$attr_list = $array['attr'];

		$type = $this->af->get('type');
		$form_file_p = C_PUBLISH_DIR . $form_file . '.php';
		$form_file_m = C_PUBLISH_DIR . $form_file . C_MOBILE_SUFFIX . '.php';
		$list = array();
		if ($type == 'r') {
			if (is_file($form_file_p)) {
				$list[] = array('type' => 'p', 'src' => $form_file_p, 'dst' => $form_file_p);
			}
			if (is_file($form_file_m)) {
				$list[] = array('type' => 'm', 'src' => $form_file_m, 'dst' => $form_file_m);
			}
			if ((bool)$list) {
				$done_msg = '既存テンプレートを再構築しました';
			} else {
				$done_msg = '既存テンプレートはありません';
			}
		} else if ($type == 'm') {
			$list[] = array('type' => 'm', 'src' => './app/template/ja/skelton_m.html', 'dst' => $form_file_m);
			$done_msg = '携帯用テンプレートを作成しました';
		} else {
			$list[] = array('type' => 'p', 'src' => './app/template/ja/skelton_p.html', 'dst' => $form_file_p);
			$done_msg = 'PC用テンプレートを作成しました';
		}

		$jsparts = $this->makeJsParts_p($attr_list);
		foreach ($list as $pair) {
			extract($pair);
			if (!is_writable($dst) && is_file($dst)) {
				$this->ae->add('error', $dst . 'に書き込み権限がありません');
				return 'error';
			}

			$form_name_tmp = $type == 'm' ? mb_convert_kana($form_name, 'aks') : $form_name;
			$depth = substr_count(C_PUBLISH_DIR, '/');
			$dir = str_repeat('../', $depth - 1);
			$source = file_get_contents($src);
			$source = str_replace('{$dir}', $dir, $source);
			$source = str_replace('{$id}', $id, $source);
			$source = str_replace('{$form_file}', $dst, $source);
			$source = str_replace('{$form_name}', $form_name_tmp, $source);

			$make = 'makeParts_' . $type;
			list($confirms, $forms) = $this->$make($attr_list);
			$this->replace(sprintf('<?/* confirm_part_%s */?>', $type), $confirms, $source);
			$this->replace(sprintf('<?/* form_part_%s */?>', $type), $forms, $source);
			$this->replace(sprintf('<?/* js_part_%s */?>', $type), $jsparts, $source);
			if (strpos($source, C_FORM_DIR) === false) {
				unlink($data_file);
			}

			$fp = fopen($dst, 'w+b');
			if (!$fp || !fwrite($fp, $source)) {
				$this->ae->add('error', $dst . 'に書き込めませんでした');
				return 'error';
			}
			fclose($fp);
		}

		$host = getEnv('HTTP_HOST');
		$port = getEnv('SERVER_PORT');
		$ssl = getEnv('HTTPS');
		$ssl = strToLower($ssl);
		$is_ssl = $ssl == 'on';
		$scheme = $is_ssl ? 'https://' : 'http://';
		if ($port != null) {
			if ((!$is_ssl && $port !== '80') || ($is_ssl && $port !== '443')) {
				$port = ':' . $port;
			} else {
				$port = '';
			}
		}
		$url = $scheme . $host . $port . '/';

		$body = $this->makeBody($attr_list);
		$receipt = <<<EOM
──────────────────────────────
　　本日はお問い合わせ頂き誠にありがとうございました。
　--------------------------------------------------------
　　このメールに心当りがない場合は、お手数ですが、
　　下記URLのサイト運営者までご連絡願います。
　　$url
──────────────────────────────

$body
EOM;
		$receipt = str_replace("\n", ',', $receipt);
		$data->set('body', $body);
		$data->set('receipt', $receipt);
		if (!$data->write()) {
			$this->ae->add('error', $data_file . 'に書き込めませんでした');
			return 'error';
		}

		$this->ae->add('error', $done_msg);
		$this->af->set('form_file', $form_file);
		$this->af->set('form_name', $form_name);
		return 'tmplPublish';
	}
	function replace($mark, $line_list, &$source)
	{
		$mk = preg_quote($mark, '/');
		$regexp = sprintf('/^(.+%s[\r\n]+)(.*)([\r\n]*%s.+)$/s', $mk, $mk);
		if (!preg_match($regexp, $source)) {
			return false;
		}

		array_unshift($line_list, $mark);
		$line_list[] = $mark;
		$source = preg_replace("/$mk.+?$mk/s", implode("\n", $line_list), $source);
		return true;
	}
	function makeJsParts_p($attr_list)
	{
		$builder = new FormBuilder();
		$list = array();
		foreach ($attr_list as $attr) {
			extract($attr);

			$tn = $type_name[0];
			$required = $required ? 'true' : 'false';
			$line = sprintf("\t\t\t{id: '%s', type: '%s', name: '%s', required: %s, ", $id, $tn, h($name), $required);
			if ($tn === 'T') {
				// $line .= sprintf("min: %s, max: %s, ", $min, $max);
				$line .= sprintf("min: %s, ", $min);
				if ($custom != '') {
					$custom = strToLower(substr($custom, 5));
					if ($custom == 'repeat') {
						$line .= sprintf("repeat: true, ");
					} else {
						$line .= sprintf("regexp: '%s', ", $custom);
					}
				}
			}
			$line = rtrim($line, ', ') . '}';
			$list[] = $line;
		}
		$line = implode(",\n", $list);
		return array($line);
	}
	function makeParts_p($attr_list)
	{
		$builder = new FormBuilder();
		$forms = array();
		$confirms = array();
		foreach ($attr_list as $i => $attr) {
			extract($attr);

			$esc_name = $name;
			$esc_suffix = $suffix;
			$esc_name = h($esc_name);
			$esc_suffix = h($esc_suffix);

			$form = $builder->$type_name($attr);
			$form .= $esc_suffix;
			if ($example !== '') {
				$example = h($example);
$form .= <<<EOM
<br />
							<em class="example">$example</em>
EOM;
			}

			if ($group === '0' && $i > 0) {
$forms[] = <<<EOM
					</td>
				</tr>
EOM;
$confirms[] = <<<EOM
			</td>
		</tr>
EOM;
			}

			if ($group === '0' || $i === 0) {
				$required = $required === '1' ? '<em class="required">※</em>' : '';
$forms[] = <<<EOM
				<tr>
					<th>$esc_name$required</th>
					<td>
EOM;
$confirms[] = <<<EOM
		<tr>
			<th>$esc_name$required</th>
			<td>
EOM;
			}

$forms[] = <<<EOM
						<p>
							<?error(\${$id}_e)?>
							$form
						</p>
EOM;
$confirms[] = <<<EOM
				<p><?=\${$id}_c?>$esc_suffix</p>
EOM;
		}

		if ((bool)$forms) {
$forms[] = <<<EOM
					</td>
				</tr>
EOM;
$confirms[] = <<<EOM
			</td>
		</tr>
EOM;
		}
		return array($confirms, $forms);
	}
	function makeParts_m($attr_list)
	{
		$builder = new FormBuilder();
		$forms = array();
		$confirms = array();
		foreach ($attr_list as $i => $attr) {
			extract($attr);

			$esc_name = $name;
			$esc_suffix = $suffix;
			$esc_name = h($esc_name);
			$esc_suffix = h($esc_suffix);

			$form = $builder->$type_name($attr);
			$form = $this->lightweighting($form);
			$form .= $esc_suffix;
			if ($example !== '') {
				$example = h($example);
$form .= <<<EOM
<br />
			<em class="example">$example</em>
EOM;
			}

			if ($group === '0' && $i > 0) {
$forms[] = <<<EOM
		</p>
EOM;
$confirms[] = <<<EOM
	</p>
EOM;
			}

			if ($group === '0' || $i === 0) {
				$required = $required === '1' ? '<em class="required">※</em>' : '';
$forms[] = <<<EOM
		<p>
			■$esc_name$required<br />
EOM;
$confirms[] = <<<EOM
	<p>
		■$esc_name$required<br />
EOM;
			}

$forms[] = <<<EOM
			<?error_br(\${$id}_e)?>
			$form<br />
EOM;
$confirms[] = <<<EOM
		<?=\${$id}_c?>$esc_suffix<br />
EOM;
		}

		if ((bool)$forms) {
$forms[] = <<<EOM
		</p>
EOM;
$confirms[] = <<<EOM
	</p>
EOM;
		}
		return array($confirms, $forms);
	}
	function makeBody($attr_list)
	{
		$body = '';
		foreach ($attr_list as $i => $attr) {
			extract($attr);
			if ($group === '0' || $i === 0) {
				$body .= "\n■$name\n";
			}
			$body .= "{\$$id}$suffix\n";
		}
		$body = trim($body);
		$body = str_replace("\n", ',', $body);
		return $body;
	}
	function lightweighting($string)
	{
		$regexp = array(
			'/size="\d+?"/',
			'/cols="\d+?"/',
			'/rows="\d+?"/',
			'/style="ime-mode: active.+?"/',
			'/style="ime-mode: .+?"/',
			'/ id="\w+?"/',
			'/ class="\w+?"/',
			'/<label.+?>/',
			'/<\/label>/'
		);
		$after = array(
			'size="14"',
			'cols="14"',
			'rows="2"',
			'istyle="1"',
			'istyle="3"',
			'',
			'',
			'',
			''
		);
		return preg_replace($regexp, $after, $string);
	}
}

?>
