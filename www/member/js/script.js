$(function() {
  // .m-tab01
  //(function() {
  //  var $components = $('#contents .m-tab01');

  //  $components.each(function() {
  //    /* 定義・宣言
  //    ----------------------------------------------------------*/
  //    var $component  = $(this);
  //    var $tabs       = $component.find('.m-tab01__tabs > li');
  //    var $contents   = $component.find('.m-tab01__content');
  //    var activeClass = 'm-tab01__tab--active';

  //    function switchTab($targetTab) {
  //      var $currentTab     = $tabs.filter('.' + activeClass).eq(0);
  //      var currentTarget   = $currentTab.find('a').attr('href');
  //      var $currentContent = $(currentTarget);

  //      var target         = $targetTab.find('a').attr('href');
  //      var $targetContent = $(target);

  //      $tabs.removeClass(activeClass);
  //      $targetTab.addClass(activeClass);

  //      /***********************************************************************
  //      現在アクティブではないタブを連続でクリックした際
  //      （例えば、「すべて」がアクティブの時、「回答あり」をクリックして
  //      即「回答なし」をクリック）
  //      最初にクリックした際のアニメーション処理が残ってしまうため、
  //      コンテンツが２つ表示されてしまう。
  //      それを回避するためにアニメーション前にcurrent以外のコンテンツを
  //      アニメーション停止+非表示にする。
  //      **********************************************************************/
  //      $contents.each(function() {
  //        if ($(this).get(0) !== $currentContent.get(0)) {
  //          $(this).stop().css('display', 'none');
  //        }
  //      });

  //      $currentContent.stop().fadeOut('fast', function() {
  //        $targetContent.stop().fadeIn('fast');
  //      });
  //    }

  //    /* 初期処理
  //    ----------------------------------------------------------*/
  //    $tabs.eq(0).addClass(activeClass);
  //    $contents.eq(0).css('display', 'block');

  //    /* コールバック
  //    ----------------------------------------------------------*/
  //    $tabs.on('click', function() {
  //      var $tab = $(this);
  //      switchTab($tab);

  //      return false;
  //    });
  //  });

  //  console.log('ok');
  //  

  //})();
});
