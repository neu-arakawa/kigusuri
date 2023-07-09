<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<?php
$ua=$_SERVER['HTTP_USER_AGENT'];
if((strpos($ua,'iPhone')!==false)||(strpos($ua,'iPod')!==false)||(strpos($ua,'Android')!==false)){
echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
}else{
echo '<meta name="viewport" content="width=1024">';
}
?>

<meta name="format-detection" content="telephone=no,address=no,email=no">
<meta name="description" content="<?php e(@$description, '日本全国の漢方専門薬局・薬店の紹介、あなたの街の信頼出来る漢方薬・生薬認定薬剤師や豊かな経験を持つ先生にまずはご相談下さい。また症状からの漢方的治療法をはじめ、漢方薬、女性の健康、サプリメント、ハーブの情報を専門家がやさしく解説しています。') ?>">
<meta name="keywords" content="<?php e(@$keywords, '漢方薬,漢方薬局') ?>">

<!--Facebook -->
<?php if ($_SERVER['REQUEST_URI'] == '/'): ?>
<meta property="og:type" content="website">
<?php else: ?>
<meta property="og:type" content="article">
<?php endif; ?>
<meta property="og:title" content="<?php e(@$title, '漢方薬 漢方薬局 薬店のことなら きぐすり.com', true) ?>">
<meta property="og:url" content="<?php echo 'http://'.$_SERVER['HTTP_HOST'].htmlspecialchars($_SERVER['REQUEST_URI'],ENT_QUOTES); ?>">
<meta property="og:image" content="https://www.kigusuri.com/sp/common/img/ogp.png">
<meta property="og:description" content="<?php e(@$description, '日本全国の漢方専門薬局・薬店の紹介、あなたの街の信頼出来る漢方薬・生薬認定薬剤師や豊かな経験を持つ先生にまずはご相談下さい。また症状からの漢方的治療法をはじめ、漢方薬、女性の健康、サプリメント、ハーブの情報を専門家がやさしく解説しています。') ?>">
<meta property="fb:app_id" content="151163465003848">
<meta property="og:site_name" content="漢方薬 漢方薬局 薬店のことなら きぐすり.com">
<!--end -->
<meta name="twitter:card" content="summary">
<meta name="twitter:site" content="@kigusuri">
<meta name="verify-v1" content="l/EXK7nGh+HfRce3BIyh2iVyV1b/UrxLSKukiCUmhQM=">
<meta name="norton-safeweb-site-verification" content="lyykglvxbkdidvrjx1p3qpdp5m9qdux7j9zqnpa9-0w9mfp3rwhvw1kgjy6u02mw5813ohk83eysv5qlwokore1-kkp-3j81sffe95mwvuux14pfahoj7sl5vurhyma9">
<meta name="bitly-verification" content="371f28d1a501">
<?php if ($_SERVER['REQUEST_URI'] == '/'): ?>
<title>漢方薬 漢方薬局 薬店のことなら きぐすり.com</title>
<?php else: ?>
<title><?php e(@$title, ' 漢方薬局 相談サイト 漢方薬のきぐすり.com', true) ?></title>
<?php endif; ?>

<link rel="shortcut icon" href="/favicon.ico" type="image/vnd.microsoft.icon">
<link rel="icon" href="/favicon.ico" type="image/vnd.microsoft.icon">
<link rel="apple-touch-icon" href="/common/img/icon.png">
<link rel="stylesheet" href="/common/css/common.css">
<!--公開前にcommon.cssに統合 start-->
<link rel="stylesheet" href="/common/css/common02.css">
<!--公開前にcommon.cssに統合 end-->
<?php e(@$ex_tag_css) ?>

<?php include 'analytics.php'; ?>

</head>

<body<?php if (!empty($bodyID)) echo ' id="'.$bodyID.'"'; ?><?php if (!empty($bodyClass)) echo ' class="'.$bodyClass.'"'; ?>>

<!-- ////////////////////////////// header start -->
<header class="shop">
	<div class="header-inner fbox">
		<?php if ($_SERVER['REQUEST_URI'] == '/'): ?>
		<h1 id="header-logo"><a href="/"><img src="/common/img/logo.gif" alt="漢方薬のきぐすり.com" width="231" height="44"></a></h1>
		<?php else: ?>
		<p id="header-logo"><a href="/"><img src="/common/img/logo.gif" alt="漢方薬のきぐすり.com" width="231" height="44"></a></p>
		<?php endif; ?>
		<p class="header-shop-text"><a href="/"><img src="/common/img/logo_mypageshop.png" alt="漢方薬のきぐすり.com" width="436" height="74"></a></p>
		
		<p id="btn-spmenu" class="sp-item"><a href="#" id="panel-btn"><span id="panel-btn-icon"></span><span class="font-en menu-text"></span></a></p>
		<nav id="gnav-wrap">
	
			<div id="nav-user-wrap">
				<ul id="nav-user">
					<li><a href="sample" class="btn-mypage header-btn01">マイページトップ</a></li>
					<li><a href="sample" class="btn-history header-btn01">回答履歴</a></li>
					<li><span class="user-name">◯◯◯◯◯◯◯◯◯◯◯◯薬局<span>さん</span></span>
						<div class="user-menu-wrap">
							<ul class="user-menu">
								<li><a href="sample"><span>会員情報編集</span></a></li>
								<li><a href="sample"><span>ログアウト</span></a></li>
							</ul>
						</div></li>
				</ul>
				<p class="nav-user-top"><a href="/" target="_blank">きぐすりトップ</a></p>

			</div>
		</nav>

	</div>
</header>
<!-- ////////////////////////////// header end -->

<div id="wrapper">
  <div id="contents">

