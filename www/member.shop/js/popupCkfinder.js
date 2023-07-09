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
		_prev.find("img").remove();
        _prev.find(".publicImages").val('');
        $(this).parent().hide();
	});
	$(".popupCkfinderImage").click(function(){
		var _this = this;
		var finder = new CKFinder();
		var _prev = $(this).parents('td');
		finder.selectActionFunction = function(fileUrl) {
            _prev.find(".publicImages").val(fileUrl);
            _prev.find("img").remove();
            _prev.find('.m-table01__fileimg')
                .prepend("<img src='"+fileUrl+"' width='228' height='142' class='popupCkfinderImage' />");
            _prev.find(".m-table01__delete").show();
		}
		finder.resourceType  = 'Images';
		if($(this).attr('pharmacy_id')){
            finder.connectorInfo = 'pharmacy_id='+$(this).attr('pharmacy_id');
        }
        // finder.connectorInfo = 'admin=true';
        // finder.startupPath = "Images:/aoki/";
		finder.popup();
	});

});
