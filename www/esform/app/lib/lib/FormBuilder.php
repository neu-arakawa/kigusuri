<?php

class FormBuilder
{
	function TEXT($attr)
	{
		extract($attr);
		$style = htmlSpecialChars($style, ENT_QUOTES);
		return sprintf('<input type="text" id="%s" name="%s" size="%s" value="<?=$%s_v?>" style="%s" />',
			$id, $id, $width, $id, $style);
	}
	function TEXTAREA($attr)
	{
		extract($attr);
		$style = htmlSpecialChars($style, ENT_QUOTES);
		return sprintf('<textarea id="%s" name="%s" cols="%s" rows="%s" style="%s"><?=$%s_v?></textarea>',
			$id, $id, $width, $height, $style, $id);
	}
	function SELECT($attr)
	{
		extract($attr);
		$lines = array();
		$lines[] = sprintf('<select id="%s" name="%s">', $id, $id);
		$values = htmlSpecialChars($values, ENT_QUOTES);
		$values = explode(',', $values);
		$is_group = false;
		foreach ($values as $i => $value) {
			$num_id = $id . $i;
			if ($value[0] === '-') {
				$value = substr($value, 1);
				$lines[] = sprintf('<option value="%s"<?=$%s_v?>>%s</option>',
					'', $num_id, $value);
			} else if ($value[0] === '+') {
				if ($is_group) {
					$lines[] = '</optgroup>';
				}
				$value = substr($value, 1);
				$lines[] = sprintf('<optgroup label="%s">', $value);
				$is_group = true;
			} else {
				$lines[] = sprintf('<option value="%s"<?=$%s_v?>>%s</option>',
					$value, $num_id, $value);
			}
		}
		if ($is_group) {
			$lines[] = '</optgroup>';
		}
		$lines[] = '</select>';
		return implode("\n", $lines);
	}
	function RADIO($attr)
	{
		extract($attr);
		$lines = array();
		$lines[] = sprintf('<span id="%s" class="wrapper">', $id);
		$values = htmlSpecialChars($values, ENT_QUOTES);
		$values = explode(',', $values);
		foreach ($values as $i => $value) {
			if ($value === '') {
				$lines[] = '<br />';
			} else {
				$num_id = $id . $i;
				$lines[] = sprintf('<label for="%s"><input type="radio" id="%s" name="%s" value="%s"<?=$%s_v?> />%s</label>',
					$num_id, $num_id, $id, $value, $num_id, $value);
			}
		}
		$lines[] = '</span>';
		return implode("\n", $lines);
	}
	function CHECKBOX($attr)
	{
		extract($attr);
		$lines = array();
		$lines[] = sprintf('<span id="%s" class="wrapper">', $id);
		$values = htmlSpecialChars($values, ENT_QUOTES);
		$values = explode(',', $values);
		foreach ($values as $i => $value) {
			if ($value === '') {
				$lines[] = '<br />';
			} else {
				$num_id = $id . $i;
				$lines[] = sprintf('<label for="%s"><input type="checkbox" id="%s" name="%s[]" value="%s"<?=$%s_v?> />%s</label>',
					$num_id, $num_id, $id, $value, $num_id, $value);
			}
		}
		$lines[] = '</span>';
		return implode("\n", $lines);
	}
	function FILE($attr)
	{
		extract($attr);
		return sprintf('<input type="file" id="%s" name="%s" size="%s" />', $id, $id, $width);
	}
}

?>
