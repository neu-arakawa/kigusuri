<? require_once './app/setlang.php'; ?>
<? require_once './app/postman.php'; ?>
<<?='?'?>xml version="1.0" encoding="utf-8"<?='?'?>>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja" dir="ltr">

<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta http-equiv="content-style-type" content="text/css" />
	<meta http-equiv="content-script-type" content="text/javascript" />
	<title>「漢方薬のきぐすり.com」アンケート</title>
	<link rel="stylesheet" type="text/css" href="./app/common.css" media="all" />
	<script type="text/javascript" src="./app/prototype.js"></script>
	<script type="text/javascript" src="./app/common.js"></script>
</head>

<body>


<script type="text/javascript">
// <![CDATA[
	window.onload = function() {
		Validator.register('inputform', [
<?/* js_part_p */?>

<?/* js_part_p */?>
		]);
	};
// ]]>
</script>


<div id="container">

<div id="contents">
<? IF ($ERROR) : ?>
<h2>「漢方薬のきぐすり.com」アンケート [エラー]</h2>
<p><a href="/">ホームページへ戻る</a></p>
<div class="box">
<ul class="error">
<li><?=$ERROR?>。</li>
</ul>
<p>
<a href="./quest_form.php" onclick="history.go(-2);return false;" onkeypress="return;">入力画面へ戻る</a>
</p>
</div>
<? ELSEIF ($DONE) : ?>
<h2>「漢方薬のきぐすり.com」アンケート [完了]</h2>
<p><a href="/">ホームページへ戻る</a></p>
<p class="navi">入力画面 <span>&raquo;</span> 確認画面 <span>&raquo;</span> <em>完了画面</em></p>
<div class="box">
<ul>
<li>メールを送信しました。</li>
<li>アンケートのご協力ありがとうございました。</li>
</ul>
</div>
<? ELSEIF ($CONFIRM) : ?>
<h2>「漢方薬のきぐすり.com」アンケート [確認]</h2>
<p><a href="/">ホームページへ戻る</a></p>
<p class="navi">入力画面 <span>&raquo;</span> <em>確認画面</em> <span>&raquo;</span> 完了画面</p>
<table summary="確認内容">
<?/* confirm_part_p */?>
<tr>
<th style="background-color:#ccffcc; ">お名前<em class="required">※</em></th>
<td><p><?=$d34a_c?></p></td>
</tr>
<tr>
<th style="background-color:#ccffcc; ">性別<em class="required">※</em></th>
<td><p><?=$f3a3_c?></p></td>
</tr>
<tr>
<th style="background-color:#ccffcc; ">年齢<em class="required">※</em></th>
<td><p><?=$z842_c?></p></td>
</tr>
<tr>
<th style="background-color:#ccffcc; ">ご住所<em class="required">※</em></th>
<td>
<p><?=$m2e1_c?></p>
<p><?=$d800_c?></p>
</td>
</tr>
<tr>
<th style="background-color:#ccffcc; ">電話番号（記入例：06-1111-2222）<em class="required">※</em></th>
<td><p><?=$ifcd_c?></p></td>
</tr>
<tr>
<th style="background-color:#ccffcc; ">メールアドレス<em class="required">※</em></th>
<td><p><?=$u60d_c?></p></td>
</tr>
<tr>
<th>体の健康への不安はありますか？</th>
<td><p><?=$ld1d_c?></p></td>
</tr>
<tr>
<th>健康面で気をつけている事はありますか？<br />【複数回答可】</th>
<td>
<p><?=$x29a_c?></p>
<p><?=$h7ad_c?></p>
</td>
</tr>
<tr>
<th>漢方薬には興味はありますか？</th>
<td><p><?=$j225_c?></p></td>
</tr>
<tr>
<th>漢方専門薬局へ行ったことはありますか？</th>
<td><p><?=$fc35_c?></p></td>
</tr>
<tr>
<th>漢方薬を買うならどこで買いますか？<br />【複数回答可】</th>
<td>
<p><?=$e68c_c?></p>
</td>
</tr>
<tr>
<th>漢方薬のイメージは何色ですか？<br />【複数回答可】</th>
<td>
<p><?=$v380_c?></p>
<p><?=$u797_c?></p>
</td>
</tr>
<tr>
<th>サイト、ホームページを「お気に入り」に入れるためのポイントは？<br />【複数回答可】</th>
<td>
<p><?=$l630_c?></p>
<p><?=$g1e9_c?></p>
</td>
</tr>
<tr>
<th>「漢方薬のきぐすり.com」のトップページのイメージを選んで下さい。<br />【複数回答可】</th>
<td>
<p><?=$ke44_c?></p>
<p><?=$o552_c?></p>
</td>
</tr>
<tr>
<th>あなたが、もし「漢方薬のきぐすり.com」で紹介しているお店を1店舗選んで行くとすれば、どこのお店に行きますか？選んだ理由も教えて下さい。<br /><a href="http://www.kigusuri.com/kensaku.html" target="_blank">http://www.kigusuri.com/kensaku.html</a></th>
<td>
<p>【薬局名】<br /><?=$dba3_c?></p>
<p>【理由】<br /><?=$u24f_c?></p>
<p>【その他】<br /><?=$r907_c?></p>
</td>
</tr>
<tr>
<th>「漢方薬のきぐすり.com」を利用したことはありますか？</th>
<td><p><?=$wb29_c?></p></td>
</tr>
<tr>
<th>※上記で「はい」と答えた方のみご回答下さい。「漢方薬のきぐすり.com」をご利用される目的は？<br />【複数選択可】</th>
<td>
<p><?=$x5dc_c?></p>
<p><?=$c62b_c?></p>
</td>
</tr>
<tr>
<th>※「漢方薬のきぐすり.com」でお気に入りのコーナーは？<br />【複数選択可】</th>
<td><p><?=$le53_c?></p></td>
</tr>
<tr>
<th>「漢方薬のきぐすり.com」に欲しい情報は？</th>
<td><p><?=$k979_c?></p></td>
</tr>
<tr>
<th>今後も「漢方薬のきぐすり.com」を利用したいですか？</th>
<td><p><?=$l6ec_c?></p></td>
</tr>
<?/* confirm_part_p */?>
</table>
<form action="./quest_form.php" method="post">
<fieldset>
<legend>「漢方薬のきぐすり.com」アンケート</legend>
<input type="hidden" name="id" value="2" />
<?=$hidden?>
<p style="text-align: center;">
<input type="button" value="やり直す" class="button" onclick="history.back();return false;" onkeypress="return;" />
<input type="submit" name="_send" value="この内容を送信する" class="button" style="width: 11em;" />
</p>
</fieldset>
</form>
<? ELSE : ?>
<h2 style="font-size:16px;">「漢方薬のきぐすり.com」アンケートに答えて日本産『高麗ニンジン』をもらおう！</h2>
<p>掘りたて日本産『高麗ニンジン』をアンケート回答者から５名様にプレゼント！<br />
<font color=red>★アンケート受付期間は終了しました。たくさんのご応募ありがとうございました！</font></p>
<p><a href="/">ホームページへ戻る</a></p>
<p class="navi"><em>入力画面</em> <span>&raquo;</span> 確認画面 <span>&raquo;</span> 完了画面</p>
<p><em class="required">※</em>は必須項目です。</p>
<? if ($errors) { ?>
<p class="error">入力エラーを訂正して下さい。</p>
<? } ?>
<form action="./quest_form.php" method="post" enctype="multipart/form-data" accept-charset="utf-8" id="inputform">
<fieldset>
<legend>「漢方薬のきぐすり.com」アンケート</legend>
<input type="hidden" name="id" value="2" />
<table summary="送信フォーム">
<?/* form_part_p */?>
<tr>
<th style="background-color:#ccffcc; ">お名前<em class="required">※</em></th>
<td>
<p>
<?error($d34a_e)?>
<input type="text" id="d34a" name="d34a" size="30" value="<?=$d34a_v?>" style="ime-mode: auto;" />
</p>
</td>
</tr>
<tr>
<th style="background-color:#ccffcc; ">性別<em class="required">※</em></th>
<td>
<p>
<?error($f3a3_e)?>
<span id="f3a3" class="wrapper">
<label for="f3a30"><input type="radio" id="f3a30" name="f3a3" value="男"<?=$f3a30_v?> />男</label>
<label for="f3a31"><input type="radio" id="f3a31" name="f3a3" value="女"<?=$f3a31_v?> />女</label>
</span>
</p>
</td>
</tr>
<tr>
<th style="background-color:#ccffcc; ">年齢<em class="required">※</em></th>
<td>
<p>
<?error($z842_e)?>
<select id="z842" name="z842">
<option value=""<?=$z8420_v?>>-選択項目--</option>
<option value="19歳以下"<?=$z8421_v?>>19歳以下</option>
<option value="20代"<?=$z8422_v?>>20代</option>
<option value="30代"<?=$z8423_v?>>30代</option>
<option value="40代"<?=$z8424_v?>>40代</option>
<option value="50代"<?=$z8425_v?>>50代</option>
<option value="60歳以上"<?=$z8426_v?>>60歳以上</option>
</select>
</p>
</td>
</tr>
<tr>
<th style="background-color:#ccffcc; ">ご住所<em class="required">※</em></th>
<td>
<p>
<?error($m2e1_e)?>
<select id="m2e1" name="m2e1">
<option value=""<?=$m2e10_v?>>選択して下さい</option>
<option value="北海道"<?=$m2e11_v?>>北海道</option>
<option value="青森県"<?=$m2e12_v?>>青森県</option>
<option value="岩手県"<?=$m2e13_v?>>岩手県</option>
<option value="宮城県"<?=$m2e14_v?>>宮城県</option>
<option value="秋田県"<?=$m2e15_v?>>秋田県</option>
<option value="山形県"<?=$m2e16_v?>>山形県</option>
<option value="福島県"<?=$m2e17_v?>>福島県</option>
<option value="茨城県"<?=$m2e18_v?>>茨城県</option>
<option value="栃木県"<?=$m2e19_v?>>栃木県</option>
<option value="群馬県"<?=$m2e110_v?>>群馬県</option>
<option value="埼玉県"<?=$m2e111_v?>>埼玉県</option>
<option value="千葉県"<?=$m2e112_v?>>千葉県</option>
<option value="東京都"<?=$m2e113_v?>>東京都</option>
<option value="神奈川県"<?=$m2e114_v?>>神奈川県</option>
<option value="山梨県"<?=$m2e115_v?>>山梨県</option>
<option value="長野県"<?=$m2e116_v?>>長野県</option>
<option value="新潟県"<?=$m2e117_v?>>新潟県</option>
<option value="富山県"<?=$m2e118_v?>>富山県</option>
<option value="石川県"<?=$m2e119_v?>>石川県</option>
<option value="福井県"<?=$m2e120_v?>>福井県</option>
<option value="岐阜県"<?=$m2e121_v?>>岐阜県</option>
<option value="静岡県"<?=$m2e122_v?>>静岡県</option>
<option value="愛知県"<?=$m2e123_v?>>愛知県</option>
<option value="三重県"<?=$m2e124_v?>>三重県</option>
<option value="滋賀県"<?=$m2e125_v?>>滋賀県</option>
<option value="京都府"<?=$m2e126_v?>>京都府</option>
<option value="大阪府"<?=$m2e127_v?>>大阪府</option>
<option value="兵庫県"<?=$m2e128_v?>>兵庫県</option>
<option value="奈良県"<?=$m2e129_v?>>奈良県</option>
<option value="和歌山県"<?=$m2e130_v?>>和歌山県</option>
<option value="鳥取県"<?=$m2e131_v?>>鳥取県</option>
<option value="島根県"<?=$m2e132_v?>>島根県</option>
<option value="岡山県"<?=$m2e133_v?>>岡山県</option>
<option value="広島県"<?=$m2e134_v?>>広島県</option>
<option value="山口県"<?=$m2e135_v?>>山口県</option>
<option value="徳島県"<?=$m2e136_v?>>徳島県</option>
<option value="香川県"<?=$m2e137_v?>>香川県</option>
<option value="愛媛県"<?=$m2e138_v?>>愛媛県</option>
<option value="高知県"<?=$m2e139_v?>>高知県</option>
<option value="福岡県"<?=$m2e140_v?>>福岡県</option>
<option value="佐賀県"<?=$m2e141_v?>>佐賀県</option>
<option value="長崎県"<?=$m2e142_v?>>長崎県</option>
<option value="熊本県"<?=$m2e143_v?>>熊本県</option>
<option value="大分県"<?=$m2e144_v?>>大分県</option>
<option value="宮崎県"<?=$m2e145_v?>>宮崎県</option>
<option value="鹿児島県"<?=$m2e146_v?>>鹿児島県</option>
<option value="沖縄県"<?=$m2e147_v?>>沖縄県</option>
<option value="海外"<?=$m2e148_v?>>海外</option>
</select>
</p>
<p>
<?error($d800_e)?>
<input type="text" id="d800" name="d800" size="50" value="<?=$d800_v?>" style="ime-mode: auto;" />
</p>
</td>
</tr>
<tr>
<th style="background-color:#ccffcc; ">電話番号（記入例：06-1111-2222）<em class="required">※</em></th>
<td>
<p>
<?error($ifcd_e)?>
<input type="text" id="ifcd" name="ifcd" size="30" value="<?=$ifcd_v?>" style="ime-mode: auto;" />
</p>
</td>
</tr>
<tr>
<th style="background-color:#ccffcc; ">メールアドレス<em class="required">※</em></th>
<td>
<p>
<?error($u60d_e)?>
<input type="text" id="u60d" name="u60d" size="30" value="<?=$u60d_v?>" style="ime-mode: auto;" />
</p>
</td>
</tr>
<tr>
<th>体の健康への不安はありますか？</th>
<td>
<p>
<?error($ld1d_e)?>
<span id="ld1d" class="wrapper">
<label for="ld1d0"><input type="radio" id="ld1d0" name="ld1d" value="ある"<?=$ld1d0_v?> />ある</label>
<label for="ld1d1"><input type="radio" id="ld1d1" name="ld1d" value="ない"<?=$ld1d1_v?> />ない</label>
</span>
</p>
</td>
</tr>
<tr>
<th>健康面で気をつけている事はありますか？【複数回答可】</th>
<td>
<p>
<?error($x29a_e)?>
<span id="x29a" class="wrapper">
<label for="x29a0"><input type="checkbox" id="x29a0" name="x29a[]" value="サプリメントを飲んでいる"<?=$x29a0_v?> />サプリメントを飲んでいる</label>
<label for="x29a1"><input type="checkbox" id="x29a1" name="x29a[]" value="運動を心がけている"<?=$x29a1_v?> />運動を心がけている</label>
<label for="x29a2"><input type="checkbox" id="x29a2" name="x29a[]" value="薬を飲んでいる"<?=$x29a2_v?> />薬を飲んでいる</label>
<label for="x29a3"><input type="checkbox" id="x29a3" name="x29a[]" value="食事（栄養）を考えて食べている"<?=$x29a3_v?> />食事（栄養）を考えて食べている</label>
<label for="x29a4"><input type="checkbox" id="x29a4" name="x29a[]" value="何もしていない"<?=$x29a4_v?> />何もしていない</label>
</span>
</p>
<p>
【その他】<br />
<?error($h7ad_e)?>
<textarea id="h7ad" name="h7ad" cols="50" rows="2" style="ime-mode: auto;"><?=$h7ad_v?></textarea>
</p>
</td>
</tr>
<tr>
<th>漢方薬には興味はありますか？</th>
<td>
<p>
<?error($j225_e)?>
<span id="j225" class="wrapper">
<label for="j2250"><input type="radio" id="j2250" name="j225" value="はい"<?=$j2250_v?> />はい</label>
<label for="j2251"><input type="radio" id="j2251" name="j225" value="いいえ"<?=$j2251_v?> />いいえ</label>
</span>
</p>
</td>
</tr>
<tr>
<th>漢方専門薬局へ行ったことはありますか？</th>
<td>
<p>
<?error($fc35_e)?>
<span id="fc35" class="wrapper">
<label for="fc350"><input type="radio" id="fc350" name="fc35" value="はい"<?=$fc350_v?> />はい</label>
<label for="fc351"><input type="radio" id="fc351" name="fc35" value="いいえ"<?=$fc351_v?> />いいえ</label>
</span>
</p>
</td>
</tr>
<tr>
<th>漢方薬を買うならどこで買いますか？<br />【複数回答可】</th>
<td>
<p>
<?error($e68c_e)?>
<span id="e68c" class="wrapper">
<label for="e68c0"><input type="checkbox" id="e68c0" name="e68c[]" value="病院（クリニック）"<?=$e68c0_v?> />病院（クリニック）</label>
<label for="e68c1"><input type="checkbox" id="e68c1" name="e68c[]" value="漢方専門薬局"<?=$e68c1_v?> />漢方専門薬局</label>
<label for="e68c2"><input type="checkbox" id="e68c2" name="e68c[]" value="百貨店の薬局"<?=$e68c2_v?> />百貨店の薬局</label><br />
<label for="e68c3"><input type="checkbox" id="e68c3" name="e68c[]" value="ドラックストアー"<?=$e68c3_v?> />ドラックストアー</label>
<label for="e68c4"><input type="checkbox" id="e68c4" name="e68c[]" value="インターネット・通販"<?=$e68c4_v?> />インターネット・通販</label>
</span>
</p>
</td>
</tr>
<tr>
<th>漢方薬のイメージは何色ですか？<br />【複数回答可】</th>
<td>
<p>
<?error($v380_e)?>
<span id="v380" class="wrapper">
<label for="v3800"><input type="checkbox" id="v3800" name="v380[]" value="赤色"<?=$v3800_v?> />赤色</label>
<label for="v3801"><input type="checkbox" id="v3801" name="v380[]" value="青色"<?=$v3801_v?> />青色</label>
<label for="v3802"><input type="checkbox" id="v3802" name="v380[]" value="黄色"<?=$v3802_v?> />黄色</label>
<label for="v3803"><input type="checkbox" id="v3803" name="v380[]" value="黒色"<?=$v3803_v?> />黒色</label>
<label for="v3804"><input type="checkbox" id="v3804" name="v380[]" value="緑色"<?=$v3804_v?> />緑色</label>
<label for="v3805"><input type="checkbox" id="v3805" name="v380[]" value="茶色"<?=$v3805_v?> />茶色</label>
</span>
</p>
<p>
<?error($u797_e)?>
<input type="text" id="u797" name="u797" size="50" value="<?=$u797_v?>" style="ime-mode: auto;" />
</p>
</td>
</tr>
<tr>
<th>サイト、ホームページを「お気に入り」に入れるためのポイントは？<br />【複数回答可】</th>
<td>
<p>
<?error($l630_e)?>
<span id="l630" class="wrapper">
<label for="l6300"><input type="checkbox" id="l6300" name="l630[]" value="デザイン"<?=$l6300_v?> />デザイン</label>
<label for="l6301"><input type="checkbox" id="l6301" name="l630[]" value="文字の大きさ"<?=$l6301_v?> />文字の大きさ</label>
<label for="l6302"><input type="checkbox" id="l6302" name="l630[]" value="更新頻度"<?=$l6302_v?> />更新頻度</label>
<label for="l6303"><input type="checkbox" id="l6303" name="l630[]" value="内容"<?=$l6303_v?> />内容</label><br />
<label for="l6304"><input type="checkbox" id="l6304" name="l630[]" value="挿絵や写真の美しさ"<?=$l6304_v?> />挿絵や写真の美しさ</label>
</span>
</p>
<p>
<?error($g1e9_e)?>
<input type="text" id="g1e9" name="g1e9" size="50" value="<?=$g1e9_v?>" style="ime-mode: auto;" />
</p>
</td>
</tr>
<tr>
<th>「漢方薬のきぐすり.com」のトップページのイメージを選んで下さい。<br />【複数回答可】</th>
<td>
<p>
<?error($ke44_e)?>
<span id="ke44" class="wrapper">
<label for="ke440"><input type="checkbox" id="ke440" name="ke44[]" value="明るい"<?=$ke440_v?> />明るい</label>
<label for="ke441"><input type="checkbox" id="ke441" name="ke44[]" value="見やすい"<?=$ke441_v?> />見やすい</label>
<label for="ke442"><input type="checkbox" id="ke442" name="ke44[]" value="わかりやすい"<?=$ke442_v?> />わかりやすい</label>
<label for="ke443"><input type="checkbox" id="ke443" name="ke44[]" value="かわいい"<?=$ke443_v?> />かわいい</label>
<label for="ke444"><input type="checkbox" id="ke444" name="ke44[]" value="文字が多い"<?=$ke444_v?> />文字が多い</label><br />
<label for="ke445"><input type="checkbox" id="ke445" name="ke44[]" value="暗い"<?=$ke445_v?> />暗い</label>
<label for="ke446"><input type="checkbox" id="ke446" name="ke44[]" value="見にくい"<?=$ke446_v?> />見にくい</label>
<label for="ke447"><input type="checkbox" id="ke447" name="ke44[]" value="わかりにくい"<?=$ke447_v?> />わかりにくい</label>
<label for="ke448"><input type="checkbox" id="ke448" name="ke44[]" value="可愛くない"<?=$ke448_v?> />可愛くない</label>
<label for="ke449"><input type="checkbox" id="ke449" name="ke44[]" value="文字が少ない"<?=$ke449_v?> />文字が少ない</label>
</span>
</p>
<p>
【その他】<br />
<?error($o552_e)?>
<input type="text" id="o552" name="o552" size="50" value="<?=$o552_v?>" style="ime-mode: auto;" />
</p>
</td>
</tr>
<tr>
<th>あなたが、もし「漢方薬のきぐすり.com」で紹介しているお店を1店舗選んで行くとすれば、どこのお店に行きますか？選んだ理由も教えて下さい。<br /><a href="http://www.kigusuri.com/kensaku.html" target="_blank">http://www.kigusuri.com/kensaku.html</a></th>
<td>
<p>
【薬局名】<br />
<?error($dba3_e)?>
<input type="text" id="dba3" name="dba3" size="50" value="<?=$dba3_v?>" style="ime-mode: auto;" />
</p>
<p>
【理由】<br />
<?error($u24f_e)?>
<span id="u24f" class="wrapper">
<label for="u24f0"><input type="checkbox" id="u24f0" name="u24f[]" value="家から近いから"<?=$u24f0_v?> />家から近いから</label>
<label for="u24f1"><input type="checkbox" id="u24f1" name="u24f[]" value="職場から近いから"<?=$u24f1_v?> />職場から近いから</label><br />
<label for="u24f2"><input type="checkbox" id="u24f2" name="u24f[]" value="お店の外観写真や店内写真をみて気に入ったから"<?=$u24f2_v?> />お店の外観写真や店内写真をみて気に入ったから</label><br />
<label for="u24f3"><input type="checkbox" id="u24f3" name="u24f[]" value="スタッフ写真を見て安心できそうだから"<?=$u24f3_v?> />スタッフ写真を見て安心できそうだから</label><br />
<label for="u24f4"><input type="checkbox" id="u24f4" name="u24f[]" value="薬局の紹介の文章がいいと思ったから"<?=$u24f4_v?> />薬局の紹介の文章がいいと思ったから</label><br />
<label for="u24f5"><input type="checkbox" id="u24f5" name="u24f[]" value="悩みの症状の詳細が書いてあるから"<?=$u24f5_v?> />悩みの症状の詳細が書いてあるから</label>
<label for="u24f6"><input type="checkbox" id="u24f6" name="u24f[]" value="更新頻度が多いから"<?=$u24f6_v?> />更新頻度が多いから</label>
</span>
</p>
<p>
【その他】<br />
<?error($r907_e)?>
<textarea id="r907" name="r907" cols="50" rows="3" style="ime-mode: auto;"><?=$r907_v?></textarea>
</p>
</td>
</tr>
<tr>
<th>「漢方薬のきぐすり.com」を利用したことはありますか？</th>
<td>
<p>
<?error($wb29_e)?>
<span id="wb29" class="wrapper">
<label for="wb290"><input type="radio" id="wb290" name="wb29" value="はい"<?=$wb290_v?> />はい</label>
<label for="wb291"><input type="radio" id="wb291" name="wb29" value="いいえ"<?=$wb291_v?> />いいえ</label>
</span>
</p>
</td>
</tr>
<tr>
<th>※上記で「はい」と答えた方のみご回答下さい。「漢方薬のきぐすり.com」をご利用される目的は？<br />【複数選択可】</th>
<td>
<p>
<?error($x5dc_e)?>
<span id="x5dc" class="wrapper">
<label for="x5dc0"><input type="checkbox" id="x5dc0" name="x5dc[]" value="漢方専門の薬局・薬店の検索"<?=$x5dc0_v?> />漢方専門の薬局・薬店の検索</label>
<label for="x5dc1"><input type="checkbox" id="x5dc1" name="x5dc[]" value="漢方薬を買いたい"<?=$x5dc1_v?> />漢方薬を買いたい</label><br />
<label for="x5dc2"><input type="checkbox" id="x5dc2" name="x5dc[]" value="病気や症状について知りたい"<?=$x5dc2_v?> />病気や症状について知りたい</label>
<label for="x5dc3"><input type="checkbox" id="x5dc3" name="x5dc[]" value="漢方薬について知りたい"<?=$x5dc3_v?> />漢方薬について知りたい</label><br />
<label for="x5dc4"><input type="checkbox" id="x5dc4" name="x5dc[]" value="プレゼントに応募したい"<?=$x5dc4_v?> />プレゼントに応募したい</label>
</span>
</p>
<p>
【その他】<br />
<?error($c62b_e)?>
<textarea id="c62b" name="c62b" cols="50" rows="2" style="ime-mode: auto;"><?=$c62b_v?></textarea>
</p>
</td>
</tr>
<tr>
<th>※「漢方薬のきぐすり.com」でお気に入りのコーナーは？<br />【複数選択可】</th>
<td>
<p>
<?error($le53_e)?>
<span id="le53" class="wrapper">
<label for="le530"><input type="checkbox" id="le530" name="le53[]" value="病気の悩みを漢方で"<?=$le530_v?> />病気の悩みを漢方で</label>
<label for="le531"><input type="checkbox" id="le531" name="le53[]" value="病気と漢方"<?=$le531_v?> />病気と漢方</label>
<label for="le532"><input type="checkbox" id="le532" name="le53[]" value="女性の生活と健康"<?=$le532_v?> />女性の生活と健康</label><br />
<label for="le533"><input type="checkbox" id="le533" name="le53[]" value="ナチュラルサプリメント"<?=$le533_v?> />ナチュラルサプリメント</label>
<label for="le534"><input type="checkbox" id="le534" name="le53[]" value="ハーバルライフ"<?=$le534_v?> />ハーバルライフ</label><br />
<label for="le535"><input type="checkbox" id="le535" name="le53[]" value="あなたの街の漢方相談薬局・薬店へ"<?=$le535_v?> />あなたの街の漢方相談薬局・薬店へ</label><br />
<label for="le536"><input type="checkbox" id="le536" name="le53[]" value="薬剤師が厳選「今月の一押し商品」"<?=$le536_v?> />薬剤師が厳選「今月の一押し商品」</label><br />
<label for="le537"><input type="checkbox" id="le537" name="le53[]" value="薬局・薬店の先生による「健康サポート」"<?=$le537_v?> />薬局・薬店の先生による「健康サポート」</label><br />
<label for="le538"><input type="checkbox" id="le538" name="le53[]" value="李先生の漢方的体質チェック表"<?=$le538_v?> />李先生の漢方的体質チェック表</label><br />
<label for="le539"><input type="checkbox" id="le539" name="le53[]" value="御影先生の漢方あれこれ"<?=$le539_v?> />御影先生の漢方あれこれ</label><br />
<label for="le5310"><input type="checkbox" id="le5310" name="le53[]" value="神田先生のあーだこうだの爽快!植物学"<?=$le5310_v?> />神田先生のあーだこうだの爽快!植物学</label><br />
<label for="le5311"><input type="checkbox" id="le5311" name="le53[]" value="二階堂先生の「粗食で長寿」食生活テスト"<?=$le5311_v?> />二階堂先生の「粗食で長寿」食生活テスト</label><br />
<label for="le5312"><input type="checkbox" id="le5312" name="le53[]" value="寺田先生の四季の色「草木染」"<?=$le5312_v?> />寺田先生の四季の色「草木染」</label>
<label for="le5313"><input type="checkbox" id="le5313" name="le53[]" value="淺野先生の和の香り"<?=$le5313_v?> />淺野先生の和の香り</label><br />
<label for="le5314"><input type="checkbox" id="le5314" name="le53[]" value="生薬（きぐすり）のふるさと"<?=$le5314_v?> />生薬（きぐすり）のふるさと</label>
<label for="le5315"><input type="checkbox" id="le5315" name="le53[]" value="きぐすりトピックス"<?=$le5315_v?> />きぐすりトピックス</label>
<label for="le5316"><input type="checkbox" id="le5316" name="le53[]" value="その他"<?=$le5316_v?> />その他</label>
</span>
</p>
</td>
</tr>
<tr>
<th>「漢方薬のきぐすり.com」に欲しい情報は？</th>
<td>
<p>
<?error($k979_e)?>
<textarea id="k979" name="k979" cols="50" rows="3" style="ime-mode: auto;"><?=$k979_v?></textarea>
</p>
</td>
</tr>
<tr>
<th>今後も「漢方薬のきぐすり.com」を利用したいですか？</th>
<td>
<p>
<?error($l6ec_e)?>
<span id="l6ec" class="wrapper">
<label for="l6ec0"><input type="radio" id="l6ec0" name="l6ec" value="はい"<?=$l6ec0_v?> />はい</label>
<label for="l6ec1"><input type="radio" id="l6ec1" name="l6ec" value="いいえ"<?=$l6ec1_v?> />いいえ</label>
</span>
</p>
</td>
</tr>
<?/* form_part_p */?>
</table>
<p style="text-align: center;">
<input type="reset" value="やり直す" class="button" />
<input type="submit" value="確認画面へ" class="button" style="width: 10em;" />
</p>
</fieldset>
</form>
<? ENDIF; ?>
</div>

<?/* 改変禁止 */?>
<div id="copyright">
<address><a href="http://www.mt312.com/">ES-FORM</a></address>
</div>

</div>
	
<script src="http://www.google-analytics.com/urchin.js" type="text/javascript"> 
</script> 
<script type="text/javascript"> 
_uacct = "UA-2810667-1";
urchinTracker();
</script>
</body>
</html>