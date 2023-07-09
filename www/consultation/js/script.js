$(function() {

    // ボタンクリック時の処理
    $('#contents .m-question01').find('.m-question01__resolve-btn a').click(function(e){
       msg  = 'よろしければ「はい」、キャンセルする場合は「いいえ」をクリックしてください。';
       msg += '<br>※解決済みにすると、回答を得られなくなります。';
       var url = $(this).attr('url');
       var csrf_token  = $('[name=csrf_token]').val();
       var question_id = $('[name=question_id]').val();
       openConfirm('この相談を解決済みにします。よろしいですか？', msg)
           .done(function(){ 
                //リクエスト内容
                data = {
                    csrf_token  : csrf_token,
                    question_id : question_id
                };
                //レスポンスを書き込む
                $.ajax({
                    type: "post",
                    dataType: "text",
                    url: url,
                    data:data,
                    xhrFields: {
                       withCredentials: true
                    },
                    beforeSend: function() {
                      loading.show();
                    }
                }).done(function (response) {
                    // form.replaceWith(response.html);
                    if(response == 'ok'){
                        $('.m-question01__resolve-btn').remove();
                        $('.m-question01__resolved').show();
                        $('.m-question01').addClass('m-question01--resolved');
                        // $(".m-question01__resolved img").animate({ 
                            // width: "89px",
                            // opacity: 1 }, 600 );
    
                        $(".m-question01__resolved img")
                        .css({transform:' scale(5)'})
                        .animate({rotate:'0deg',width: "89px",scale:'1',opacity: 1},300,'linear');
    
                        $('.m-answer-area01__answer-box').each(function() {
                           if($(this).find('li .m-txt-area01__delete').length){
                            $(this).find('li .m-txt-area01__delete').remove();
                           }
                           else if($(this).find('li form').length){
                            $(this).find('li:eq(1)').remove();
                           }
                        });
                    }
                })
                .fail(function(jqXHR) {
                    document.head.innerHTML = "";
                    document.body.innerHTML = jqXHR.responseText;
                })
                .always(function( jqXHR, textStatus ) {
                    console.log('end');
                    loading.hide();
                });
    
                closeConfirm();
                  
           })
           .fail(function(){ 
               closeConfirm();
           })
    });
    
    //削除
    $('#contents .question_delete').click(function(e){
       var form = $(this).parents('form'); 
        
       if(form.find('textarea').length){
           val = form.find('textarea').val().replace(/[\n\r\s]/g,"");
           if(val === ''){
              form.find('textarea').parents('.m-table01').find('.m-table01__error').text('削除理由は必須です。ご入力ください');
              return false;
           }
           if(10 > val.length ){
              form.find('textarea').parents('.m-table01').find('.m-table01__error').text('削除理由は10文字以上で入力してください');
              return false;
           }
       }
    
       msg  = 'よろしければ「はい」、キャンセルする場合は「いいえ」をクリックしてください。';
       msg += '<br>※削除の取り消しはできません。';
       openConfirm( $(this).attr('comfirm'), msg )
           .done(function(){ 
               closeConfirm();
               form.submit();
           })
           .fail(function(){ 
               closeConfirm();
           })
    });


    // txt-area01 もっと見る機能
    (function() {
    
        /***********************************************************************
        レイアウト切替時の処理
        **********************************************************************/
        currentWidth = window.innerWidth || document.documentElement.clientWidth;
        if(currentWidth <= 768){
            if($('.m-txt-area01__txt').length){
              $('.m-txt-area01__txt').readmore({
              	speed: 700,
              	collapsedHeight: 100,
              	moreLink: '<a href="#" class="m-bal01__more read-more">...もっと見る</a>',
              	lessLink: false
              });
            }
        }
    
      // var $components = $('#contents .m-txt-area01');
    
      // $components.each(function() {
        // [>**********************************************************************
        // 変数・関数宣言
        // **********************************************************************/
        // var $component = $(this);
        // var $moreBtn   = $component.find('.m-txt-area01__more');
        // var $omitTxt   = $component.find('.m-txt-area01__omit');
    
        // [>**********************************************************************
        // コールバック
        // **********************************************************************/
        // $moreBtn.on('click', function() {
          // $omitTxt.show();
          // $moreBtn.hide();
    
          // return false;
        // });
    
      // });
    })();
    
    // if($('.response_btn').length){
    var csrf_token  = $('[name=csrf_token]').val();
    var question_id = $('[name=question_id]').val();
    // $('.response_btn').on('click', function() {
    $(document).on('click', '.response', function () {
    
        var form = $(this).parents('form');
        val = form.find('textarea').val().replace(/[\n\r\s]/g,"");
        if(val === ''){
           form.find('.tx-att').text('コメントを入力してください');
           return false;
        }
        if(10 > val.length ){
           form.find('.tx-att').text('コメントを10文字以上で入力してください');
           return false;
        }
    
        $(this).prop("disabled", true)
        url  = $('[name=response_url]').val();
        data = {
            csrf_token  : csrf_token,
            question_id : question_id,
            answer_id   : form.find('[name=answer_id]').val(),
            content     : form.find('textarea').val()
        };
        
        //レスポンスを書き込む
        $.ajax({
            type: "post",
            dataType: "json",
            url: url,
            data:data,
            xhrFields: {
               withCredentials: true
            },
            beforeSend: function() {
              // form.hide();
            }
        }).done(function (response) {
            var html = response.html; 
            form.replaceWith(function() {
                return $(html).hide().fadeIn(1000);
            });
        })
        .fail(function(jqXHR) {
            alert("コメント送信中にエラーが発生しました。\n\n再度、ページを読み込みしてください。");
            // document.head.innerHTML = "";
            // document.body.innerHTML = jqXHR.responseText;
        })
        .always(function( jqXHR, textStatus ) {
            console.log('end');
            // form.show();
        });
    
        return false;
    });
    $('.m-txt-area01--comment').find('[name=content]').focus(function(){
        $('.m-txt-area01--comment .tx-att').text("");
    });
    
    $(document).on('click', '.response_delete', function () {
        var form  = $(this).parents('form');
        var _this = this;
        openConfirm('コメント削除します。よろしいでしょうか？')
             .done(function(){ 
                  url  = $('[name=response_delete_url]').val();
                  data = {
                      csrf_token  : csrf_token,
                      answer_id   : form.find('[name=answer_id]').val(),
                      response_id : form.find('[name=response_id]').val()
                  };
                  
                  $(_this).prop("disabled", true)
                  //レスポンスを書き込む
                  $.ajax({
                      type: "post",
                      dataType: "json",
                      url: url,
                      data:data,
                      xhrFields: {
                         withCredentials: true
                      },
                      beforeSend: function() {
                        // loading.show();
                      }
                  }).done(function (response) {
                      console.log(response);
                      // form.replaceWith(response.html);
                      var html = response.html; 
                      form.replaceWith(function() {
                          return $(html).hide().fadeIn(1000);
                      });
                  })
                  .fail(function(jqXHR) {
                      document.head.innerHTML = "";
                      document.body.innerHTML = jqXHR.responseText;
                  })
                  .always(function( jqXHR, textStatus ) {
                      console.log('end');
                      // loading.hide();
                  });
             })
             .fail(function(){ 
                 closeConfirm();
             })
    });

    $('.block-main form').submit(function(){
       val = $('.block-main form').find('[name=content]').val().replace(/[\n\r\s]/g,"");
       if(val === ''){
          $('.block-main .tx-att').text('相談内容を入力してください');
          return false;
       }
       if(10 > val.length ){
          $('.block-main .tx-att').text('相談内容を10文字以上で入力してください');
          return false;
       }
       return true;
    });
    
    $('.block-main form').find('[name=content]').focus(function(){
        $('.block-main .tx-att').text("");
    });
       
    
    //参考になった
    $('.m-good-btn01__btn-body').on('click', function() {
         url  = $('[name=like_answer_url]').val();
         data = {
             csrf_token  : csrf_token,
             answer_id   : $(this).attr('answer_id')
         };
         if ( $(this).data("disabled") )return false;
    
         $(this).addClass('is-click');
         $(this).data("disabled", true);
    
         cnt = parseInt($(this).parents('.m-good-btn01').find('.m-good-btn01__cnt').html())+1;
         $(this).parents('.m-good-btn01').find('.m-good-btn01__cnt').text(cnt)
         cnt = parseInt($('.m-answer-area01__num-big:eq(1)').text())+1;
         $('.m-answer-area01__num-big:eq(1)').text(cnt);
    
         $(this).parents('.m-good-btn01').find('.m-good-btn01__cnt')
             .css("color", "#00c272")
             .transition({
               scale: "2.15"
             }, 0)
             .transition({
               scale: "1",
               color: "#434343"
             }, 700, "easeOutSine");
    
         var _this = this;
         $.ajax({
             type: "post",
             dataType: "text",
             url: url,
             data:data,
             beforeSend: function() {
               // loading.show();
             }
         }).done(function (response) {
           if(response == 'ok' ){
               $(_this).data("disabled", false);
           }
         })
         .fail(function(jqXHR) {
             document.head.innerHTML = "";
             document.body.innerHTML = jqXHR.responseText;
         })
         .always(function( jqXHR, textStatus ) {
               // loading.hide();
         });
    });
    
    //絞り込みや並び順
    $(document).on('change','#fr select',function(){
        var p = [];
        $("#fr select").each(function(){
            p.push( $(this).attr('name')+'='+$(this).val() );
        });
        if(p.length){
            location.href = location.pathname+'?'+p.join('&');
        }
        else {
            location.href = location.pathname;
        }
    });
});
