var openConfirm  = null;
var closeConfirm = null;
var loading = null;
$(function() {

	// ロールオーバー
	$('.rollover').rollOver();
	
	// ロールオーバー (透明度変え版)
	$('.fadeover img').fadeOver();
	
	// スムーズスクロール
	$('.smooth a').smoothLink();

	
	var ua = navigator.userAgent;
	var $spMenu = $('#sp-header-menu');
	var $headNavi = $('#header-nav-wrap');


	/**
	* SP Menu
	*/
	
	//MENUボタン
	var $menuBtn = $('#panel-btn');
	var $menuWrap = $('#gnav-wrap');

	$menuBtn.on('click', function() {
		$(this).toggleClass('close');
		
		if($menuBtn.hasClass('close')) {
			$menuWrap.show().addClass('show');
			$('#contents,footer,#pagetop,#copyright').css({
				display  : 'none',
				overflow : 'hidden'
			});
		} else {
			$menuWrap.hide().removeClass('show');
			$('#contents,footer,#pagetop,#copyright').css({
				display  : ' block',
				overflow : 'auto'
			});
		}

		return false;
	});


  /**
   * breakPoint
   */
  $(window).breakPoint( {
    smartPhoneWidth : 768,
    tabletWidth     : 0,
    pcMediumWidth   : 0,

    onSmartPhoneEnter : function(){
      // スマホモード時の処理を書く

      $spMenu.removeClass('close');
      $headNavi.hide();
	  
	  /*$('.sp-disable').on('click', function(){
		  return false;
	  });*/

    },
    onPcEnter : function() {
			// PCモード時の処理を書く
			//グローバルナビ
			$('#gnav > li').navigation({
				activeParentClass: 'active'
			});
			$('.user-name').on('click',function(){
				$('.user-menu-wrap').toggleClass('user-menu-open');
			});

    },
    onSmartPhoneLeave : function() {
      // スマホモード解除時の処理を書く
      $spMenu.removeClass('close');
      $headNavi.show();
      $('#wrapper').show();
			$('#gnav-wrap').css('display', 'block');
			$('#contents').css('overflow', 'visible');
			$('#contents').css('display', 'block');

    },
    onPcLeave : function() {
      // PCモード解除時の処理を書く

    }
  } );


  /**
   * Tel link
   */
  if(ua.indexOf('iPhone') > 0
    && ua.indexOf('iPod') === -1
    || ua.indexOf('Android') > 0
    && ua.indexOf('Mobile') > 0
    && ua.indexOf('SC-01C') === -1
    && ua.indexOf('A1_07') === -1 ) {

    function convertToAnchorTag( str )
    {
    	// 電話番号だと思われる文字列を抽出
    	var phone_array = str.match( /\+?[0-9]+[\-\x20]?[0-9]+[\-\x20]?[0-9]+[\-\x20]?[0-9]+/g );
        console.log(str);
        console.log(phone_array);
    	var cursor = 0;
    	for ( var i = 0; phone_array != null && i < phone_array.length; i++ ) {
    
    		// ハイフンとスペースを削除
    		var tmp = phone_array[i];
    		tmp = tmp.replace( /[\-\x20]/g, '' );
    		if ( tmp.length < 10 ) {
    			// 10桁未満は電話番号とみなさない
    			continue;
    		}
    
    		// aタグ文字列を生成
    		var tag_a = '<a href="tel:' + tmp + '">' + phone_array[i] + '</a>';
    
    		// 置換する電話番号の出現位置を取得
    		var start = str.indexOf( phone_array[i], cursor );
    
    		// 出現した電話番号を置換
    		str = str.slice( 0, start ) + tag_a + str.slice( start + phone_array[i].length );
    		cursor = start + tag_a.length;
    	}
    
    	return str;
    }

    // 画像
    $('.tel-link img').each(function() {
      var alt = $(this).attr('alt');
      $(this).wrap($('<a>').attr('href', 'tel:' + alt.replace(/-/g, '')));
    } );

    // デバイステキスト
    $('.tel-linktext').each(function() {
      var str = $(this).html();
      $(this).addClass('sp-tel-linktext');
      // $(this).html($('<a>').attr('href', 'tel:' + str.replace(/-/g, '')).append(str + '</a>'));
      $(this).html(convertToAnchorTag(str));
    } );
  }
  
	/**
	* pagetop
	*/
	$('#pagetop img').on('click',function(){
		$('html,body').animate({ scrollTop: 0 }, 500);
		return false;
	});

    //キーワード検索
    if($('.m-kampo-search').length){
        $('.m-kampo-search form').submit(function(){
             keyword = $('.m-kampo-search form').find('input').val().replace(/[\n\r\s\/]/g,"");
             if(keyword === ''){
                $('.m-kampo-search .tx-att').text('キーワードを入力してください');
                return false;
             }
             if(keyword.length < 1){
                $('.m-kampo-search .tx-att').text('1文字以上で入力してください');
                return false;
             }
             if(keyword.length > 30){
                $('.m-kampo-search .tx-att').text('30文字以内で入力してください');
                return false;
             }
             if(keyword.length > 30){
                $('.m-kampo-search .tx-att').text('30文字以内で入力してください');
                return false;
             }
             url = '/consultation/list/keyword/'+ encodeURIComponent(keyword)  +'/';
             location.href = url;
             return false;
        });
        $('.m-kampo-search form').find('input').focus(function(){
            $('.m-kampo-search .tx-att').text("");
        });

    }
    
    // modal01
    (function() {
      //モーダルイベント
      var $component = $('#contents .m-question01__modal');
      openConfirm = function(title,detail){
          $component.find('.m-modal01__tit').text(title);
          if(detail === undefined){
              $component.find('.m-modal01__detail').hide();
          }
          else {
              $component.find('.m-modal01__detail').html(detail);
          }
          $component.find('.m-modal01').trigger('appear');
          // jQuery Deferredの処理
          var d = $.Deferred();
          $('body').data('deferred', d);
          return d.promise(); 
      }
      // ダイアログ内のボタンクリック時の処理
      var onClickDialogBtn = function(){
          return $('body').data('deferred');
      }
      // ダイアログのOKボタンを押した時の処理
      $component.find('.ok-btn').click(function(e){
          var d = onClickDialogBtn();
          if(d && d.resolve){
              d.resolve(); // 正常終了を通知
          }
      });
      // ダイアログのキャンセルボタンをおした時の処理
      $component.find('.cancel-btn').click(function(e){
          var d = onClickDialogBtn();
          if(d && d.reject){
              d.reject(); // 異常終了を通知
          }
      });

      // $('対象の.m-modal01').trigger('appear')でモーダルフェードイン
      // $('対象の.m-modal01').trigger('disappear')でモーダルフェードアウト
      var $components = $('#contents .m-modal01');

      $components.each(function() {
        /***********************************************************************
        変数・関数宣言
        **********************************************************************/
        var $component = $(this);
        var $overlay   = $component.find('.m-modal01__overlay');
        var $modal     = $component.find('.m-modal01__content');
        var $closeBtn  = $component.find('.m-modal01__close a, .m-btn01');

        function adjustModalPos() {
          var winT   = $(window).scrollTop();
          var winH   = $(window).height();
          var modalT = $modal.offset().top;
          var modalH = $modal.outerHeight();
          if (window.matchMedia("(min-width: 768px)").matches) {
            // pc
            var tmp01  = modalT - winT; // モーダルとブラウザ上端の相対位置
            var tmp02  = (winH/2 - modalH/2);
            var newTop = tmp02 - tmp01;
            // モーダル上端が画面外に行く状況は、上端が見えるように調整
            if (tmp01 + newTop < 10) {
              newTop = -1 * (tmp01 - 10);
            }
            $modal.css('top', newTop + 'px');
          } else {
            // sp
            $modal.css('top', winT - modalT + 'px');
          }
        }

        /***********************************************************************
        コールバック
        **********************************************************************/
        $component.on('appear', function() {
          $modal.removeAttr('style');
          $modal.add($overlay).fadeIn();

          adjustModalPos();
        });
        $component.on('disappear', function() {
          $modal.add($overlay).fadeOut(function() {
            $modal.removeAttr('style');
          });
        });
        $closeBtn.add($overlay).on('click', function() {
          $component.trigger('disappear');

          return false;
        });
        $(window).on('scroll', function() {
          var winT   = $(window).scrollTop();
          var winB   = winT + $(window).height();
          var modalT = $modal.offset().top;
          var modalB = modalT + $modal.outerHeight();
          var margin = 70;

          if (winT > modalB+margin || winB < modalT-margin) {
            $modal.add($overlay).fadeOut(function() {
              $modal.removeAttr('style');
            });
          }
        });

        /***********************************************************************
        レイアウト切替時の処理
        **********************************************************************/
        $(window).breakPoint({
          smartPhoneWidth : 768,
          tabletWidth     : 0,
          pcMediumWidth   : 0,
          onSmartPhoneEnter : function() {
            // スマホモード時の処理を書く
            $modal.add($overlay).removeAttr('style');
          },
          onPcEnter : function() {
            // PCモード時の処理を書く
            $modal.add($overlay).removeAttr('style');
          },
          onSmartPhoneLeave : function() {
            // スマホモード解除時の処理を書く
          },
          onPcLeave : function() {
            // PCモード解除時の処理を書く
          }
        });
        
        closeConfirm = function(){
            $component.trigger('disappear');
        };
      });
    })();
    
    //自動リンク
    $(".autolink").each(function(){
        $(this).html( $(this).html().replace(/((http|https|ftp):\/\/[\w?=&.\/-;#~%-]+(?![\w\s?&.\/;#~%"=-]*>))/g, '<a href="$1" target="_blank">$1</a> ') );
    });

    var html ='<div id="loading-bg"></div>';
    html +='<div id="loading">';
    html +='    <img src="/common/img/loading.gif" alt="loading..." width="32" height="32">';
    html +='    <p id="loadingTxt">読み込み中...</p>';
    html +='</div>';
    $('body').append(html);

    loading = $("#loading, #loading-bg");

    $('form[method=POST]:not([loading=none])').submit(function(){
        $('#loadingTxt').text('読み込み中');
        loading.show();
    });
    
    $.fn.countup = function( ) {
      var timer = 0;
      var max   = $(this).attr('max');
      var cur   = 1;
      var _this = this;
      next();
      function next() {
          if(cur <= max){
              $(_this).text(cur);
              timer = 1000 * 0.2 / cur;
              cur++;
              setTimeout(next, timer);
          }
      }
    };   

    if($('.count-num').length > 0){
        $('.count-num').countup();
    }
} );
