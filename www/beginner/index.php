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
	$title = 'はじめての方へ';
	
	//css追加
	$ex_tag_css = '<link rel="stylesheet" href="/beginner/css/style.css">';
	
	//js追加
	$ex_tag_js = '<script src="/common/js/lib/jquery.matchHeight.js"></script>
<script>
	<!-- calendar -->
	function MM_openBrWindow(theURL,winName,features) { //v2.0
	  window.open(theURL,winName,features);
	}	
	$(function() {

		$(".js-matchHeight").matchHeight();
	});
</script>
';
	
	//bodyID
	$bodyID = 'member';
	
?>
<?php include 'header.php'; ?>

	<div class="contents-header">
		<ul class="topicpath fbox">
			<li><a href="/">トップページ</a></li>
			<li>はじめての方へ</li>
		</ul>
		<h1 class="header-ttl">はじめての方へ</h1>
	</div>
	
	
	<article id="main" class="inner fbox">

<div class="lead">
<span>きぐすり.com は<br class="sp-item">漢方薬専門のポータルサイトです。</span>
漢方薬だけでなく、女性の健康やサプリメント、ハーブなどの情報を専門家がやさしく解説しています。<br>
みなさまの病気予防や健康増進に役立つ情報を日々配信しています。</p>
</div>

<div class="sec-box-wrap">
<section class="sec-box consultation-box">
<div class="sec-tit-wrap">
<img src="img/icon_consultation.png" alt="photo" class="sec-img">
<h3 class="sec-tit">心と身体のお悩みを<br class="pc-item">解決したい方</h3>
</div>
<p>全国の漢方薬に精通した薬剤師に相談できます。あなた自身や、ご家族ご友人の心と身体のお悩みをQ&amp;A形式で解決します！</p>
<ul class="sec-list">
<li>・匿名だから安心。あなたのプライバシーを守りながら、経験豊富な薬剤師がサポートします。</li>
<li>・役立つ症例も満載！過去のQ&amp;Aにあなたのお悩みを解決するヒントが。</li>
</ul>
<p class="top-cont-btn"><a href="/consultation/" class="btn05 top-cont-btn-in"><span class="icon-arrow01">漢方相談Q&amp;A</span></a></p>
</section>

<section class="sec-box srch-box">
<div class="sec-tit-wrap">
<img src="img/icon_serch.png" alt="photo" class="sec-img">
<h3 class="sec-tit">お近くの<br class="pc-item">漢方薬局・薬店を<br class="pc-item">お探しの方</h3>
</div>
<p>日本全国の漢方薬局・薬店を紹介しています。あなたの街の漢方薬局・薬店へ、ぜひ足をお運びください！</p>
<ul class="sec-list">
<li>・地図や店舗の特長、キーワードからお探しいただけます。</li>
<li>・ スマホでは、現在地から近い店舗をお探しいただけます。</li>
</ul>
<p class="top-cont-btn"><a href="/shop/" class="btn02 top-cont-btn-in"><span class="icon-arrow01">漢方薬局を探す</span></a></p>
</section>


<section class="sec-box kampo-box">
<div class="sec-tit-wrap">
<img src="img/icon_kampo.png" alt="photo" class="sec-img">
<h3 class="sec-tit">漢方薬のことを<br class="pc-item">詳しく知りたい方</h3>
</div>
<p>漢方薬のことはもちろん、女性の康やハーブ、サプリメントのことなど、各方面の専門家が詳しく解説しています！</p>
<ul class="sec-list">
<li>・漢方薬の処方と漢方薬を構成する生薬を紹介しています。</li>
<li>・体質チェック、食生活テストといった身体チェックや、薬膳レシピのご紹介など、豊富なコンテンツがあります。</li>
</ul>
<p class="top-cont-btn"><a href="/kampo/" class="btn04 top-cont-btn-in"><span class="icon-arrow01">漢方を知る</span></a></p>
</section>


</div>


<div class="sec-att-wrap">
<h3>■ご利用にあたっての注意点</h3>
<p>漢方薬を安全にご利用頂くために、必ず漢方専門の医師または薬剤師とご相談ください。<br>
「漢方薬のきぐすり.com」に掲載しております漢方処方や生薬情報は、専門家の知識や経験を生かし、一般の方々にもわかりやすいように説明しています。</p>
<ul class="sec-att-list">
<li>※ 漢方薬は決まった病状・症状にあてはまらないので、素人療法は禁物です。<br>患者様の体質や証の違いから、同じ症状であっても全く異なる漢方薬を使用することがあります。<br>
一方、様々な症状に１つの漢方薬が用いられることもありますが、漢方薬は万能ではございません。</li>
</ul>

<p>情報の中には古い文献の引用や、現在使用頻度が低くなったもの、また販売されなくなったものも含まれています。<br>流派の違いなどの
理由から処方の内容や量が異なることもあります。<br>「漢方薬のきぐすり.com」では、それら過去から移り変わる情報も加えて紹介して
おります。<br>よって、その点をふまえて漢方薬などをご購入、ご使用の際は、安全にご利用頂くために、必ず漢方専門の医師または薬剤師とご相談ください。</p>
<p>ご覧になられる方の健康増進に役立てていただければ幸いと願っています。</p>
<p>具体的な注意例：次の人はご使用前に必ず医師又は薬剤師にご相談ください。</p>

<ul class="sec-att-list">
<li>・医師の治療を受けている人</li>
<li>・妊娠又は妊娠していると思われる人</li>
<li>・のぼせが強く赤ら顔で体力の充実している人</li>
<li>・今までに薬により発疹・発赤、かゆみ等を起こしたことがある人</li>
<li>・その他、アレルギー体質の人</li>
<li>・胃腸の弱い人</li>
<li>・下痢しやすい人…など</li>
</ul>

<p>素人療法や無理な継続使用をされますと、症状が悪化したり、意外な副作用を見たり、食べ物との相互作用により体調を崩す場合がありますので、くれぐれもご注意ください。</p>

</div>

	

		
	</article>
	
<?php include 'footer.php'; ?>
