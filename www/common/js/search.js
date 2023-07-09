$(function() {

	//タブ
	$('#srch-category').show();
	$('.head-tab-nav > li:first-child').addClass('active')
	$('.shop-tab-nav a[href^="#"]').on('click',function(e){
		$('.shop-tab-nav .active').removeClass('active');
		$(this).parent().addClass('active');
		$('.tab-body').hide();
		$($(this).attr('href')).fadeIn('slow');
	
		e.preventDefault();
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
    },
    onPcEnter : function() {
      // PCモード時の処理を書く

    },
    onSmartPhoneLeave : function() {
      // スマホモード解除時の処理を書く
    },
    onPcLeave : function() {
      // PCモード解除時の処理を書く

    }
  } );

  //スマホ閲覧時にはスマホ用のURLにする
  if ((navigator.userAgent.indexOf("iPhone") > 0  && 
       navigator.userAgent.indexOf("iPad") == -1) || 
       navigator.userAgent.indexOf("iPod") > 0    || 
       navigator.userAgent.indexOf("Android") > 0) {

      $('a.sp_link').each(function(i, elem) {
          if($(this).attr('sp') != ''){
              $(this).attr('href', $(this).attr('sp') );
          }
      });
  }

  //都道府県と市区町村連動
  var  cities  = false;
  if($('#addr1').length){
    cities  = $.parseJSON($('#addr1').attr('data-param'));
  }
  $("select[name='pref']").change( function() {
    $("select[name=addr1]").html("");
    $("select[name=addr1]").append("<option value=''></option>");
    for( var i in cities[$(this).val()]) {
        $("select[name=addr1]").append("<option value="+cities[$(this).val()][i]['addr1']+">"+
            cities[$(this).val()][i]['addr1']+
            "("+cities[$(this).val()][i]['cnt']+")</option>");
    }
  });
  
  //ヒストリーバック対策
  if($("select[name='pref']").length > 0 && $("select[name='pref']").val() != ''){
    addr1 = $("select[name=addr1]").val();
    $("select[name='pref']").change();
    $("select[name=addr1]").val(addr1);
  }

  //一覧表示
  $('.result-box a img').each(function(i, elem){
        var imgobj = new Image();
        $(imgobj).load(function(){
            if(imgobj.width < imgobj.height){
                $(elem).parents('section').addClass('img-v');
            }
            else {
                $(elem).parents('section').addClass('img-h');
            }
        }).attr('src',$(elem).attr('src'));
/*
        $(this).load(function() {
            if($(this).width() < $(this).height()){
                $(this).parents('section').addClass('img-v');
            }
            else {
                $(this).parents('section').addClass('img-h');
            }
        });
*/
  });

  //詳細画像
  $('.shop-thumb img').each(function(i, elem){
        var imgobj = new Image();
        $(imgobj).load(function(){
            if(imgobj.width < imgobj.height){
                $(elem).parents('.shop-header').addClass('img-v');
            }
            else {
                $(elem).parents('.shop-header').addClass('img-h');
            }
        }).attr('src',$(elem).attr('src'));
/*
        $(this).load(function() {
            if($(this).width() < $(this).height()){
                $(this).parents('.shop-header').addClass('img-v');
            }
            else {
                $(this).parents('.shop-header').addClass('img-h');
            }
        });
*/
  });
  $('.shop-comment-photos img').each(function(i, elem){
        var imgobj = new Image();
        $(imgobj).load(function(){
            if(imgobj.width < imgobj.height){
                $(elem).parents('li').addClass('img-v');
            }
            else {
                $(elem).parents('li').addClass('img-h');
            }
        }).attr('src',$(elem).attr('src'));
/*
        $(this).load(function() {
            if($(this).width() < $(this).height()){
                $(this).parents('li').addClass('img-v');
            }
            else {
                $(this).parents('li').addClass('img-h');
            }
        });
*/
  });

  //日本地図リンク
  $('[name=Map] area').each(function(i, elem){
    url = '/shop/search/prefectures/'+$(this).attr('alt')+'/';
    $(this).attr('href', url);  
  });

  //スマホ都道府県から探す
  $('#prefectures_search button').on('click',function(e){
    pref = $('[name=pref]').val();
    url  = $('#prefectures_search').attr('action').replace( /:pref/g , pref ) ;
    window.location.href = url;
  	e.preventDefault();
  });

  if($("[data-email]").length){
    code = $("[data-email]").data("email");
    email = '';
    for(var i = 0; i < code.length; i++) {
        email += String.fromCharCode( code[i] );
    }
    $("[data-email]").parent().html('<a href="mailto:'+email+'">'+email+'</a>');
  }

} );
