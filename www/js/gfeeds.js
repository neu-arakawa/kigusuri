/**
 * Google AJAX Feed API (Open Feeds Download)
 *
 * (c)2009 Tenderfeel
 *  http://tenderfeel.xsrv.jp/
 *
 * --- 設定 ----------------------------------
 * feedURI    + 取得するFeedのURI
 * resultID   + 表示する要素のID
 * max        + 表示件数
 * text       + 本文の文字数（allにすると全文）
 * feedBlock  + 全体を入れる要素のタグ名
 * titleBox   + タイトルを入れる要素のタグ名
 * dateBox    + 日時を入れる要素のタグ名
 * contentBox + 本文を入れる要素のタグ名
----------------------------------------------*/
var gfConfig = {
	"feedURI":"http://kigusuri.blog32.fc2.com/?xml",
	"resultID":"feed",
	"max":3,
	"text":0,
	"feedBlock":"dl",
	"titleBox":"dt",
	"dateBox":"dd",
	"contentBox":"dd"
}

/*　超デフォルトのソース。こっちでも表示できます。

function feedLoaded() {
   var feedControl = new google.feeds.FeedControl();
   feedControl.addFeed(gfConfig.feedURI);
   feedControl.draw(document.getElementById(gfConfig.resultID));
}*/

function feedLoaded() {
/*@cc_on _d=document;eval('var document=_d')@*/
	var feed = new google.feeds.Feed(gfConfig.feedURI);
	feed.load(function(result) {
		if (!result.error) {
			var container = document.getElementById(gfConfig.resultID);
			container.innerHTML ="";
			var feedBlock = document.createElement(gfConfig.feedBlock);
			
			for (var i = 0; i < gfConfig.max ; i++) {
			var entry = result.feed.entries[i];
			var titleBox = document.createElement(gfConfig.titleBox);
			var dateBox = document.createElement(gfConfig.dateBox);
			var contentBox = document.createElement(gfConfig.contentBox);
			titleBox.className = "title";
			dateBox.className = "date";
			contentBox.className = "snippet";
			titleBox.innerHTML='<a href="'+entry["link"]+'" rel="nofollow external">'+entry["title"]+'</a>';
			dateBox.innerHTML = new Date(entry["publishedDate"]).toLocaleString();
			var str = entry["content"].replace(/\s/,"");
			if(gfConfig.text!="all"||gfConfig.text==0){
				str = str.replace(/<\/?[^>]+>/gi, "");
				str = str.substring(0, gfConfig.text)+"...";
			}
			contentBox.innerHTML = str;
			feedBlock.appendChild(titleBox);
/*
			feedBlock.appendChild(dateBox);
			feedBlock.appendChild(contentBox);
*/
			container.appendChild(feedBlock);
		}
	}
	});
}