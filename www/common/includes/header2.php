<?php
$auth   = ''; //権限
$page   = array(); //ページURL
$member = array(); //会員情報
if(preg_match('/^\/{1,}member\.shop/', $_SERVER['REQUEST_URI'])){
    if(!empty($_SESSION['pharmacy']['logined'])){
        $auth = 'pharmacy';
        $member = $_SESSION['pharmacy']['member'];
        $pages  = $_SESSION['pharmacy']['pages'];
    }
    else {
        $auth = 'none_pharmacy';
    }
}
else {
    if(!empty($_SESSION['front']['logined'])){
        $auth   = 'member';
        $member = $_SESSION['front']['member'];
        $pages  = $_SESSION['front']['pages'];
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">

<?php
if( !empty($device) && $device === 'mobile' ){
    echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
}else{
    echo '<meta name="viewport" content="width=1024">';
}
?>
<meta http-equiv="X-UA-Compatible" content="IE=edge" >
<meta name="format-detection" content="telephone=no,address=no,email=no">
<meta name="description" content="<?php eh(@$description, '日本全国の漢方専門薬局・薬店の紹介、あなたの街の信頼出来る漢方薬・生薬認定薬剤師や豊かな経験を持つ先生にまずはご相談下さい。また症状からの漢方的治療法をはじめ、漢方薬、女性の健康、サプリメント、ハーブの情報を専門家がやさしく解説しています。') ?>">
<meta name="keywords" content="<?php eh(@$keywords, '漢方薬,漢方薬局') ?>">

<!--Facebook -->
<?php if ($_SERVER['REQUEST_URI'] == '/'): ?>
<meta property="og:type" content="website">
<?php else: ?>
<meta property="og:type" content="article">
<?php endif; ?>
<meta property="og:title" content="<?php eh(@$title, '漢方薬 漢方薬局 薬店のことなら きぐすり.com', true) ?>">
<meta property="og:url" content="<?php echo 'http://'. h($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']); ?>">
<meta property="og:image" content="https://www.kigusuri.com/common/img/ogp.png">
<meta property="og:description" content="<?php eh(@$description, '日本全国の漢方専門薬局・薬店の紹介、あなたの街の信頼出来る漢方薬・生薬認定薬剤師や豊かな経験を持つ先生にまずはご相談下さい。また症状からの漢方的治療法をはじめ、漢方薬、女性の健康、サプリメント、ハーブの情報を専門家がやさしく解説しています。') ?>">
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
<title><?php eh(@$title, ' 漢方薬 漢方薬局 薬店のことなら きぐすり.com', true) ?></title>
<?php endif; ?>
<link rel="shortcut icon" href="/favicon.ico" type="image/vnd.microsoft.icon">
<link rel="icon" href="/favicon.ico" type="image/vnd.microsoft.icon">
<link rel="apple-touch-icon" href="/common/img/icon.png">
<link rel="stylesheet" href="/common/css/common.css">

<?php e(@$ex_tag_css) ?>

<?php include 'analytics.php'; ?>
</head>

<body<?php if (!empty($bodyID)) echo ' id="'.$bodyID.'"'; ?><?php if (!empty($bodyClass)) echo ' class="'.$bodyClass.'"'; ?>>

<!-- ////////////////////////////// header start -->
<?php if(empty($auth)){ ?>
<header>
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<script type="text/javascript">
$(function(){
  $('#acNavg li a').each(function(){
    var $href = $(this).attr('href');
    if(location.href.match($href)) {
      $(this).parent().addClass('current');
    } else {
      $(this).parent().removeClass('current');
    }
  });
});
</script>
<?php }elseif($auth === 'member'){ ?>
<header class="member">
<?php }elseif($auth === 'pharmacy' || $auth === 'none_pharmacy'){ ?>
<header class="shop">
<?php } ?>
	<div class="header-inner fbox">
		<?php if ($_SERVER['REQUEST_URI'] == '/'): ?>
		<h1 id="header-logo"><a href="/"><img src="/common/img/logo.png" alt="漢方薬のきぐすり.com" width="231" height="44"></a></h1>
		<?php else: ?>
		<p id="header-logo"><a href="/"><img src="/common/img/logo.png" alt="漢方薬のきぐすり.com" width="231" height="44"></a></p>
		<?php endif; ?>

        <?php if(preg_match('/pharmacy/',$auth) && $bodyID !=='page404'){ ?>
        <p class="header-shop-text"><a href="/member.shop/"><img src="/common/img/logo_mypageshop.png" alt="漢方薬のきぐすり.com" width="380" height="47"></a></p>
        <?php } ?>

		<p id="btn-spmenu" class="sp-item"><a href="#" id="panel-btn"><span id="panel-btn-icon"></span><span class="font-en menu-text"></span></a></p>
		<nav id="gnav-wrap">
            <?php if($auth !== 'pharmacy' && $auth !== 'none_pharmacy'){ ?>
			<ul id="gnav">
				<li id="gnav-qa"><a href="/consultation/"><span class="icon-qa">みんなの漢方相談Q&amp;A</span></a></li>
				<li id="gnav-shop"><a href="/shop/"><span class="icon-srch">漢方薬局を探す</span></a></li>
				<li id="gnav-kampo"><a href="/kampo/"><span class="icon-kampo">漢方を知る</span></a></li>
            <!--<li id="gnav-beginner"><a href="/beginner/"><span class="icon-beginner">はじめての方へ</span></a></li>-->
            </ul>
            <p></p>
            <p></p>
            
            <!-- SP用サイト内検索ボックス -->
            
              <div class="gcse-area sp-item" class="l-search">
                <form id="gcse-sp-form" action="https://www.kigusuri.com/search.php">
                  <input  class="gcse-text" type="text" name="q" />
                  <button class="gcse-btn"><img src="/common/img/icon_search.svg" width="15" height="15" alt="検索"></button>
                </form>
              </div>
              <script type="text/javascript" src="//www.google.com/cse/brand?form=gcse-sp-form&lang=ja"></script>
            
            
            <!-- <div class="seach-box sp-item" id="gsc">
            
            <script>
            (function() {
              var cx = '012185455868773970056:f3a0nezd12u';
              var gcse = document.createElement('script');
              gcse.type = 'text/javascript';
              gcse.async = true;
              gcse.src = 'https://cse.google.com/cse.js?cx=' + cx;
              var s = document.getElementsByTagName('script')[0];
              s.parentNode.insertBefore(gcse, s);
            })();
            </script>
            <gcse:searchbox-only></gcse:searchbox-only>
            </div> -->
            <?php } ?>

			<div id="nav-user-wrap">

            <?php if($auth !== 'pharmacy' && $auth !== 'none_pharmacy'){ ?>
                <!-- PC用サイト内検索ボックス -->
                <div class="gcse-area pc-item" class="l-search">
                  <form id="gcse-pc-form" action="https://www.kigusuri.com/search.php">
                    <input  class="gcse-text" type="text" name="q" />
                    <button class="gcse-btn"><img src="/common/img/icon_search.svg" width="15" height="15" alt="検索"></button>
                  </form>
                </div>
                <script type="text/javascript" src="//www.google.com/cse/brand?form=gcse-pc-form&lang=ja"></script>
                <!-- <div class="seach-box pc-item" id="gsc">
                <script>
                (function() {
                  var cx = '012185455868773970056:f3a0nezd12u';
                  var gcse = document.createElement('script');
                  gcse.type = 'text/javascript';
                  gcse.async = true;
                  gcse.src = 'https://cse.google.com/cse.js?cx=' + cx;
                  var s = document.getElementsByTagName('script')[0];
                  s.parentNode.insertBefore(gcse, s);
                })();
                </script>
                <gcse:searchbox-only></gcse:searchbox-only>
                </div> -->
            <?php } ?>

				<ul id="nav-user">
                <?php if(empty($auth)){ ?>
					<li><a href="/member/login/" class="btn-login">ログイン</a></li>
					<li><a href="/member/regist/" class="btn-entry header-btn01">新規会員登録</a></li>
                <?php }elseif($auth === 'member'){ ?>

					<li><a href="<?php eh($pages['qa_request']); ?>" class="btn-form header-btn01">新規相談フォーム</a></li>
					<li><span class="user-name"><?php eh($member['nickname']); ?><span>さん</span></span>
						<div class="user-menu-wrap">
							<ul class="user-menu">
								<li><a href="<?php eh($pages['qa_history']); ?>"><span>相談履歴</span></a></li>
								<li><a href="<?php eh($pages['member_edit']); ?>"><span>会員情報編集</span></a></li>
								<li><a href="<?php eh($pages['member_logout']); ?>"><span>ログアウト</span></a></li>
							</ul>
						</div></li>
                <?php }elseif($auth === 'pharmacy'){ ?>
					<li><a href="<?php eh($pages['pharmacy_mypage']); ?>" class="btn-mypage header-btn01">マイページトップ</a></li>
					<li><a href="<?php eh($pages['answer_history']); ?>" class="btn-history header-btn01">回答履歴</a></li>
					<li><span class="user-name"><?php eh($member['name']); ?><span>さん</span></span>
						<div class="user-menu-wrap">
							<ul class="user-menu">
								<li><a href="<?php eh($pages['pharmacy_edit']); ?>"><span>会員情報編集</span></a></li>
								<li><a href="<?php eh($pages['pharmacy_logout']); ?>"><span>ログアウト</span></a></li>
							</ul>
						</div></li>
                <?php } ?>

				</ul>

                <?php if($auth === 'pharmacy'){ ?>
				<p class="nav-user-top"><a href="/" target="_blank">きぐすりトップ</a></p>
                <?php } ?>

			</div>
		</nav>

	</div>

</header>


<!-- ////////////////////////////// header end -->

<div id="wrapper">
  <div id="contents">
