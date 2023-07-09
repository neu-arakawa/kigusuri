<?php
	//共通
	require_once substr($_SERVER['SCRIPT_FILENAME'], 0, -strlen($_SERVER['SCRIPT_NAME'])).'/common/includes/init.php';
	
	//メタディスクリプション
	$description = '';
	
	//メタキーワード
	$keywords = '';
	
	//Facebook　全ページ共通の場合は空白にしてください
	$fbimage = '';
	
	//タイトル
	$title = 'ページが見つかりませんでした。';
	
	//css追加
	$ex_tag_css = '<link rel="stylesheet" href="/common/css/shop.css">';
	
	//js追加
	$ex_tag_js = '<script src="/common/js/search.js"></script>
';
	
	//bodyID
	$bodyID = 'page404';
	
?>
<?php include 'header.php'; ?>

	
	
	
<div class="main-wrap">
<article id="main" class="inner fbox">
<img src="/common/img/tx404.png" alt="お探しのページは見つかりませんでした。" width="530" height="254" class="pc-item">
<img src="/common/img/tx404_sp.png" alt="お探しのページは見つかりませんでした。" width="530" height="254" class="sp-item">
<p>申し訳ございません。<br class="sp-item">  お探しのページは見つかりませんでした。<br>
一時的にアクセスができない状況にあるか、移動もしくは削除された可能性があります。<br>
<br>
大変お手数ですが、<a href="/">トップページ</a>か<a href="/sitemap.html">サイトマップ</a>よりお探しください。</p>
	
	</article>
</div>
<?php include 'footer.php'; ?>
