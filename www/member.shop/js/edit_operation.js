$(function(){
	$(".publicImages").each(function(){
		var _prev = $(this).parents('td');
		if($(this).val() != ""){
			_prev.find("img").remove();
			_prev.find('.m-table01__fileimg')
                .prepend("<img src='"+$(this).val()+"' width='228' height='142' class='popupCkfinderImage' />");
			_prev.show();
		}
        else {
            _prev.find(".m-table01__delete").hide();
        }
	});
	$(".deletePopupCkfinderImage").click(function(){
		var _prev = $(this).parents('td');
        obj = $(this).parents('td').find('input[type=file]');
        if(obj.val() !=''){
            obj.replaceWith($(this).parents('td').find('input[type=file]').clone());
            if(_prev.find(".publicImages").val() == ''){
		        _prev.find("img").remove();
                $(this).parent().hide();
            }
            else {
                _prev.find("img").attr('src',_prev.find(".publicImages").val());
            }
        }
        else {
		    _prev.find("img").remove();
            _prev.find(".publicImages").val('');
            $(this).parent().hide();
        }
	});
    
    $('#wrapper').on('change', 'input[type=file]', function(){
        if(window.File && window.FileReader) {
            var file, preview, reader;
            preview = $(this).parents('p').prev();
            file    = this.files[0];
            _this   = $(this);
            if (file) {
              if (file.type.indexOf('image/') === 0) {
                reader = new FileReader();
                reader.onloadend = function() {
                    preview.html($('<img>').attr({
                      'src': reader.result,
                      'width'  : 228,
                      'heighth': 142
                    }));
                };
                reader.readAsDataURL(file);

                if(_this.parents('.m-table01__filebox').next().find('a').length){
                    _this.parents('.m-table01__filebox').next().show();
                }

              } else {
                alert('画像ファイルを選択してください');
              }
            } else {
              preview.html('');
            }
        }
    });

});
