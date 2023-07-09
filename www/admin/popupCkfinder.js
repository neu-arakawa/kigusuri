$(function(){
	$(".popupCkfinder").each(function(){
		if($(this).next("input:hidden").val() != ""){
			var _prev = $(this).prev("div");
			var src = $(this).next("input:hidden").val();
			_prev.find("span").html("<a href='"+src+"' target='_blank'>"+decodeURI(src.split('/').pop())+"</a>");
			_prev.show();
		}
	});
	$(".deletePopupCkfinder").click(function(){
		$(this).parent().parent().find("input:hidden").val("");
		$(this).prev("span").html("");
		$(this).parent().hide();
	});
	$(".popupCkfinder").click(function(){
		var _this = this;
		var finder = new CKFinder();
		finder.selectActionFunction = function(fileUrl) {
			$(_this).next("input:hidden").val(fileUrl);
			var _prev = $(_this).prev("div");
			_prev.find("span").html("<a href='"+fileUrl+"' target='_blank'>"+decodeURI(fileUrl.split('/').pop())+"</a>");
			_prev.show();
		}
		if($(this).attr('resourceType')){
			finder.resourceType = $(this).attr('resourceType');
		}
		finder.popup();
	});

	$(".popupCkfinderImage").each(function(){
		if($(this).next("input:hidden").length > 0 && $(this).next("input:hidden").val() != ""){
			var _prev = $(this).parent().find("div");
			var src = $(this).parent().find("input:hidden").val();
			_prev.find("img").remove();
			if($(this).attr('w')){
				_prev.prepend("<img src='"+src+"' width="+$(this).attr('w')+" class='popupCkfinderImage' />");
			}else{
				_prev.prepend("<img src='"+src+"' class='popupCkfinderImage' />");
			}
			_prev.show();
		}
	});
	$(".deletePopupCkfinderImage").click(function(){
		$(this).parent().parent().find("input:hidden").val("");
		$(this).prev("img").remove();
		$(this).parent().hide();
	});
	$(".popupCkfinderImage").click(function(){
		var _this = this;
		var finder = new CKFinder();
		finder.selectActionFunction = function(fileUrl) {
            parent_ele = $(_this).parent();
			parent_ele.find("input:hidden").val(fileUrl);
			var _prev = parent_ele.find("div");
			_prev.find("img").remove();
			if($(_this).attr('w')){
				_prev.prepend("<img src='"+fileUrl+"' width="+$(_this).attr('w')+" class='popupCkfinderImage' />");
			}else{
				_prev.prepend("<img src='"+fileUrl+"' class='popupCkfinderImage' />");
			}
			_prev.show();
		}
		if($(this).attr('resize')){
			finder.id = 'image'+$(this).attr('resize');
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
