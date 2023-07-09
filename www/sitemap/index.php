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
  $title = 'サイトマップ';

  //css追加
  $ex_tag_css = '<link rel="stylesheet" href="./css/style.css">';

  //js追加
  $ex_tag_js = '';

  //bodyID
  $bodyID = 'common-page';

?>
<?php include 'header.php'; ?>

<div class="contents-header">
  <ul class="topicpath fbox">
    <li><a href="/">トップページ</a></li>
    <li>サイトマップ</li>
  </ul>
  <h1 class="header-ttl">サイトマップ</h1>
</div>
<!-- /.contents-header -->

<article id="main" class="inner shop-detail">
  <div class="inner"> 
    
    <!-- set start -->
    <section class="sitemap">
      <div class="list-set">
        <h2 class="label"><span><a href="/">トップページ</a><span class="m-arrow-btn01__icn"></span></span></h2>
        <h2 class="label"><span><a href="/consultation/">みんなの漢方相談Q&amp;A</a><span class="m-arrow-btn01__icn"></span></span></h2>
        </div>

      <div class="list-set cnt-kampo">
        <h2 class="label"><span><a href="/kampo/">漢方を知る</a><span class="m-arrow-btn01__icn"></span></span></h2>
        <div class="list-wrap-in">
          <p class="list-tit">漢方情報</p>
          <div class="list-wrap">
            <ul class="">
              <li><a href="/kampo/kampo-care/">病気の悩みを漢方で</a></li>
              <li><a href="/kampo/jiten/">漢方薬・生薬大辞典</a></li>
              <li><a href="/kampo/medicine/">病気と漢方</a></li>
              <li><a href="/kampo/mikage/">御影雅幸先生の『漢方あれこれ』</a></li>
              <li><a href="/kampo/kouda/">神田博史先生の『あーだ、こうだの爽快！植物学』</a></li>
              <li><a href="/kampo/furusato/">地図から学ぶ、生薬（きぐすり）のふるさと</a></li>
              <li><a href="/kampo/quiz/">生薬ミニミニ検定</a></li>
              <li><a href="/kampo/topics/">きぐすりトピックス</a></li>
            </ul>
          </div>
          <!-- /.list-wrap-in --></div>
        <div class="list-wrap-in">
          <p class="list-tit">漢方を知る</p>
          <div class="list-wrap">
            <ul class="">
              <li><a href="/kampo/health/">女性の生活と健康</a></li>
              <li><a href="/kampo/engei/">あなたのココロと体のために。「園芸療法」</a></li>
              <li><a href="/kampo/supplement/">天然素材サプリ</a></li>
              <li><a href="/kampo/support/">薬局・薬店の先生による健康サポート</a></li>
            </ul>
          </div>
          <p class="list-tit">レシピを知る</p>
          <div class="list-wrap">
            <ul class="">
              <li><a href="/kampo/cooking/">きぐすり的レシピ集</a></li>
              <li><a href="/kampo/nikaido/">二階堂保先生の『食べ物は薬』</a></li>
            </ul>
          </div>
          
          <!-- /.list-wrap-in --></div>
        <div class="list-wrap-in">
          <p class="list-tit">やってみよう</p>
          <div class="list-wrap">
            <ul class="">
              <li><a href="/kampo/herbal/">ハーブのある暮らし</a></li>
              <li><a href="/kampo/terada/">寺田勝彦先生の『四季の色「草木染」』</a></li>
              <li><a href="/kampo/asano/">淺野三千秋先生の『和の香り』</a></li>
            </ul>
          </div>
          <p class="list-tit">身体チェック</p>
          <div class="list-wrap">
            <ul class="">
              <!--<li><a href="/kampo/check/">李康彦先生の『漢方的体質チェック表』</a></li>-->
              <li><a href="/kampo/eat-test/">二階堂保先生の食生活テスト</a></li>
            </ul>
          </div>
          <p class="list-tit">ひとやすみ</p>
          <div class="list-wrap">
            <ul class="">
              <li><a href="/kampo/event/">参加しよう！イベント情報</a></li>
              <li><a href="javascript:;" onclick="MM_openBrWindow('/kampo/calendar.html','calendar','scrollbars=yes,resizable=yes,width=450,height=480')" class="js-matchHeight">
						花歳時記カレンダー</a></li>
            </ul>
          </div>
          
          <!-- /.list-wrap-in --></div>
      </div>
      <div class="list-set cnt-search">
        <h2 class="label"><span><a href="/shop/">漢方薬局を探す</a><span class="m-arrow-btn01__icn"></span></span></h2>
        <div class="list-wrap">
          <ul class="list-wrap-in">
            <li><a href="/shop/search/prefectures/<?php echo urlencode('北海道');?>/">北海道の漢方薬局</a></li>
            <li><a href="/shop/search/prefectures/<?php echo urlencode('青森県');?>/">青森県の漢方薬局</a></li>
            <li><a href="/shop/search/prefectures/<?php echo urlencode('岩手県');?>/">岩手県の漢方薬局</a></li>
            <li><a href="/shop/search/prefectures/<?php echo urlencode('宮城県');?>/">宮城県の漢方薬局</a></li>
            <li><a href="/shop/search/prefectures/<?php echo urlencode('秋田県');?>/">秋田県の漢方薬局</a></li>
            <li><a href="/shop/search/prefectures/<?php echo urlencode('山形県');?>/">山形県の漢方薬局</a></li>
            <li><a href="/shop/search/prefectures/<?php echo urlencode('福島県');?>/">福島県の漢方薬局</a></li>
          </ul>
          <ul class="list-wrap-in">
            <li><a href="/shop/search/prefectures/<?php echo urlencode('茨城県');?>/">茨城県の漢方薬局</a></li>
            <li><a href="/shop/search/prefectures/<?php echo urlencode('栃木県');?>/">栃木県の漢方薬局</a></li>
            <li><a href="/shop/search/prefectures/<?php echo urlencode('群馬県');?>/">群馬県の漢方薬局</a></li>
            <li><a href="/shop/search/prefectures/<?php echo urlencode('埼玉県');?>/">埼玉県の漢方薬局</a></li>
            <li><a href="/shop/search/prefectures/<?php echo urlencode('千葉県');?>/">千葉県の漢方薬局</a></li>
            <li><a href="/shop/search/prefectures/<?php echo urlencode('東京都');?>/">東京都の漢方薬局</a></li>
            <li><a href="/shop/search/prefectures/<?php echo urlencode('神奈川県');?>/">神奈川県の漢方薬局</a></li>
            <li><a href="/shop/search/prefectures/<?php echo urlencode('山梨県');?>/">山梨県の漢方薬局</a></li>
          </ul>
          <ul class="list-wrap-in">
            <li><a href="/shop/search/prefectures/<?php echo urlencode('新潟県');?>/">新潟県の漢方薬局</a></li>
            <li><a href="/shop/search/prefectures/<?php echo urlencode('富山県');?>/">富山県の漢方薬局</a></li>
            <li><a href="/shop/search/prefectures/<?php echo urlencode('石川県');?>/">石川県の漢方薬局</a></li>
            <li><a href="/shop/search/prefectures/<?php echo urlencode('福井県');?>/">福井県の漢方薬局</a></li>
            <li><a href="/shop/search/prefectures/<?php echo urlencode('長野県');?>/">長野県の漢方薬局</a></li>
            <li><a href="/shop/search/prefectures/<?php echo urlencode('岐阜県');?>/">岐阜県の漢方薬局</a></li>
            <li><a href="/shop/search/prefectures/<?php echo urlencode('静岡県');?>/">静岡県の漢方薬局</a></li>
            <li><a href="/shop/search/prefectures/<?php echo urlencode('愛知県');?>/">愛知県の漢方薬局</a></li>
            <li><a href="/shop/search/prefectures/<?php echo urlencode('三重県');?>/">三重県の漢方薬局</a></li>
          </ul>
          <ul class="list-wrap-in">
            <li><a href="/shop/search/prefectures/<?php echo urlencode('滋賀県');?>/">滋賀県の漢方薬局</a></li>
            <li><a href="/shop/search/prefectures/<?php echo urlencode('京都府');?>/">京都府の漢方薬局</a></li>
            <li><a href="/shop/search/prefectures/<?php echo urlencode('大阪府');?>/">大阪府の漢方薬局</a></li>
            <li><a href="/shop/search/prefectures/<?php echo urlencode('兵庫県');?>/">兵庫県の漢方薬局</a></li>
            <li><a href="/shop/search/prefectures/<?php echo urlencode('奈良県');?>/">奈良県の漢方薬局</a></li>
            <li><a href="/shop/search/prefectures/<?php echo urlencode('和歌山県');?>/">和歌山県の漢方薬局</a></li>
          </ul>
          <ul class="list-wrap-in">
            <li><a href="/shop/search/prefectures/<?php echo urlencode('鳥取県');?>/">鳥取県の漢方薬局</a></li>
            <li><a href="/shop/search/prefectures/<?php echo urlencode('島根県');?>/">島根県の漢方薬局</a></li>
            <li><a href="/shop/search/prefectures/<?php echo urlencode('岡山県');?>/">岡山県の漢方薬局</a></li>
            <li><a href="/shop/search/prefectures/<?php echo urlencode('広島県');?>/">広島県の漢方薬局</a></li>
            <li><a href="/shop/search/prefectures/<?php echo urlencode('山口県');?>/">山口県の漢方薬局</a></li>
            <li><a href="/shop/search/prefectures/<?php echo urlencode('徳島県');?>/">徳島県の漢方薬局</a></li>
            <li><a href="/shop/search/prefectures/<?php echo urlencode('徳島県');?>/">香川県の漢方薬局</a></li>
            <li><a href="/shop/search/prefectures/<?php echo urlencode('愛媛県');?>/">愛媛県の漢方薬局</a></li>
            <li><a href="/shop/search/prefectures/<?php echo urlencode('高知県');?>/">高知県の漢方薬局</a></li>
          </ul>
          <ul class="list-wrap-in">
            <li><a href="/shop/search/prefectures/<?php echo urlencode('福岡県');?>/">福岡県の漢方薬局</a></li>
            <li><a href="/shop/search/prefectures/<?php echo urlencode('佐賀県');?>/">佐賀県の漢方薬局</a></li>
            <li><a href="/shop/search/prefectures/<?php echo urlencode('長崎県');?>/">長崎県の漢方薬局</a></li>
            <li><a href="/shop/search/prefectures/<?php echo urlencode('熊本県');?>/">熊本県の漢方薬局</a></li>
            <li><a href="/shop/search/prefectures/<?php echo urlencode('大分県');?>/">大分県の漢方薬局</a></li>
            <li><a href="/shop/search/prefectures/<?php echo urlencode('宮崎県');?>/">宮崎県の漢方薬局</a></li>
            <li><a href="/shop/search/prefectures/<?php echo urlencode('鹿児島県');?>/">鹿児島県の漢方薬局</a></li>
            <li><a href="/shop/search/prefectures/<?php echo urlencode('沖縄県');?>/">沖縄県の漢方薬局</a></li>
          </ul>
        </div>
      </div>
      <div class="list-set cnt-other">
        <h2 class="label"><span>その他</span></h2>
        <div class="list-wrap">
          <ul class="list-wrap-in">
            <li><a href="/privacy/">プライバシーポリシー</a></li>
            <li><a href="/terms/">利用規約</a></li>
            <!-- <li><a href="/press/">プレスリリース</a></li> -->
            <!-- <li><a href="/link/">リンクについて</a></li> -->
            <li><a href="/contact_shop/">加盟店募集</a></li>
            <li><a href="/form.html">ご意見・ご感想</a></li>
          </ul>
        </div>
      </div>
      <!-- set end --> 
      
    </section>
  </div>
  <!-- /.inner --> 
</article>
<script>
	<!-- calendar -->
	function MM_openBrWindow(theURL,winName,features) { //v2.0
	  window.open(theURL,winName,features);
	}	
	$(function() {

		$(".js-matchHeight").matchHeight();
	});
</script>
<?php include 'footer.php'; ?>
