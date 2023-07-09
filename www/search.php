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
  $title = '検索結果';

  //css追加
  $ex_tag_css = '<link rel="stylesheet" href="/common/css/search-color.css">';

  //js追加
  $ex_tag_js = '';

  //bodyID
  $bodyID = 'common-page';

?>
<?php include 'header.php'; ?>



<div class="contents-header">
  <ul class="topicpath fbox">
    <li><a href="/">トップページ</a></li>
    <li>検索結果</li>
  </ul>
  <h1 class="header-ttl">検索結果</h1>
</div>
<!-- /.contents-header -->


<article id="main" class="inner search-result">
<div class="inner">
  <script>
    (function() {
      var cx = '012185455868773970056:ozisqlc6dd8';
      var gcse = document.createElement('script');
      gcse.type = 'text/javascript';
      gcse.async = true;
      gcse.src = 'https://cse.google.com/cse.js?cx=' + cx;
      var s = document.getElementsByTagName('script')[0];
      s.parentNode.insertBefore(gcse, s);
    })();
  </script>
  <gcse:searchresults-only></gcse:searchresults-only>
</div>
<!-- /.inner -->
</article>

<?php include 'footer.php'; ?>
