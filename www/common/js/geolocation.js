$(function(){
    // 位置情報取得失敗時
    var errorCallback = function (error) {
    	var message = "位置情報を取得できませんでした。端末の位置情報取得機能をONにするか、電波状況の良い場所で再度お試し下さい。";
    
    	switch (error.code) {
    	   // 位置情報取得できない場合
    	   case error.POSITION_UNAVAILABLE:
    	       message = "位置情報を取得できませんでした。端末の位置情報取得機能をONにするか、電波状況の良い場所で再度お試し下さい。";
    	       break;
    	  // Geolocation使用許可されない場合
    	  case error.PERMISSION_DENIED:
    	      message = "位置情報を取得できませんでした。端末の位置情報取得機能をONにするか、電波状況の良い場所で再度お試し下さい。";
    	      break;
    	  // タイムアウトした場合 
    	  case error.PERMISSION_DENIED_TIMEOUT:
    	      message = "位置情報を取得できませんでした。端末の位置情報取得機能をONにするか、電波状況の良い場所で再度お試し下さい。";
    	      break;
    	}
    	window.alert(message);
    };
    
    var gps = function (successCallback) {
        if (navigator.geolocation) {
            // 現在の位置情報取得を実施
            navigator.geolocation.getCurrentPosition(
                successCallback,
                errorCallback,
                {enableHighAccuracy:true, timeout:10000, maximumAge:0}
            );
        } else {
            window.alert("位置情報を取得できませんでした。端末の位置情報取得機能をONにするか、電波状況の良い場所で再度お試し下さい。");
        }
    }

    $(".routeSearch").click(function(){
        var latlon = $(this).attr('latlon');
        successCallback = function (pos) { 		        
                document.location = 'https://maps.google.com/maps?saddr=' + pos.coords.latitude + ',' + pos.coords.longitude+'&daddr='+latlon;
        };
        gps(successCallback);
    });
    $(".locationSearch").click(function(){
        successCallback = function (pos) {
                document.location = '/shop/search/location/?lat=' + pos.coords.latitude + '&lon=' + pos.coords.longitude;
        };
        gps(successCallback);
    });
});
