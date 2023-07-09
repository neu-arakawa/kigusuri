/* ulTracker: Universal Tracker (Tag Management System)
   v1.1 (c) Makoto Shimizu
   MIT License
   http://www.cms-ia.info
   features:
   - 解析などのタグをTag Management System風に一元管理
   - JS fileを非同期でLoad
   - Loadと関数実行のタイミングを制御
   - 指定domainへのアクセスのみ計測
   - cross-domain linkの自動計測
   - 離脱linkの自動計測
   - campaignとcross-domain計測のhash対応（計測後にURLからhash削除）
 */

//Google Analyticsのアカウント設定
var _gaq = [['_setAccount','UA-2810667-1'], ['_setAllowAnchor', true]];

//Facebookのアカウント設定
window.fbAsyncInit = function(){
	FB.init({appId:'151163465003848', status:true, cookie:true, xfbml:true});
};

var ulTracker = function(){

	//計測対象のdomain:「,」で複数指定可
	var internalDomains = 'kigusuri.com';

	//LoadするJS file
	// 1.タイミング (0:即, 1:DOM後, 2:計測後)
	// 2.non-SSL時のURL
	// 3.SSL時のURL (non-SSLと同じなら省略可)
	var f = [
		[0, 'http://www.google-analytics.com/ga.js', 'https://ssl.google-analytics.com/ga.js'],
		[1, 'http://www.kigusuri.com/js/ga.utils.js'],
		[1, 'http://platform.twitter.com/widgets.js'],
		[1, 'http://connect.facebook.net/ja_JP/all.js', 'https://connect.facebook.net/ja_JP/all.js'],
		[1, 'http://www.kigusuri.com/js/ga_social_tracking.js'],
		[2, 'http://apis.google.com/js/plusone.js', 'https://apis.google.com/js/plusone.js']
	];
	//条件付きでJSをロードしたい場合の例
	if (typeof jQuery != 'function')
		f.unshift([0, 'http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js', 'https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js']);

	/* ここからメイン。実行タイミングは
	   1.doAsap() - 本JSがロードされ次第
	   2.タイミングを0(即)と指定したJSファイルを全て非同期ロード
	   3.doAsapAfterJS() - 2を待ってから実行
	   4.doAfterDom() - 3が完了＆DOMがreadyになったら（なっていたら）実行
	   5.タイミングを1(DOM後)と指定したJSファイルを全て非同期ロード
	   6.doAfterDomAfterJS() - 5を待ってから実行
	   7.doTrack() - 6の関数の中で実行
	   8.doAfterTrack() - GAが_trackPageviewを実行した後に実行
	   9.タイミングを2(計測後)と指定したJSファイルを全て非同期ロード
	 */

	var cd='', f0=[], f1=[], f2=[], i=0, qd=0, l=location, w=window;
	for(i=0; i<f.length; i++){
		if (f[i][0]==0)f0.push('https:'==l.protocol?f[i][2]||f[i][1]:f[i][1]);
		if (f[i][0]==1)f1.push('https:'==l.protocol?f[i][2]||f[i][1]:f[i][1]);
		if (f[i][0]==2)f2.push('https:'==l.protocol?f[i][2]||f[i][1]:f[i][1]);
	}

	//本JSがロードされ次第、即実行する関数
	var doAsap = function(){
		//GA：日本の検索エンジン追加
		_gaq.push(
			['_addOrganic', 'excite.',          'search'],
			['_addOrganic', 'hatena.',          'word'],
			['_addOrganic', 's.luna.tv',        'q'],
			['_addOrganic', 'search.goo.ne.jp', 'MT'],
			['_addOrganic', 'search.nifty.co',  'q'],
			['_addOrganic', 'search.rakuten.co','qt'],
			['_addOrganic', 'so-net.ne.jp',     'query']
		);

		//対象ドメイン判定
		if (internalDomains){
			var d = internalDomains.split(',');
			for(i=0; i<d.length; i++){
				//現在のドメインと合致したら
				if (l.hostname.indexOf(d[i]) > -1){
					//GA：サブドメイン対策のためCookieドメイン指定
					_gaq.push(['_setDomainName', d[i]]);
					cd = d[i];
				}else{
					//GA：サイト内ドメインをリファラ扱いにしない
					_gaq.push(['_addIgnoredRef', d[i]]);
				}
			}
			//GA：複数ドメインの場合は
			if (d.length > 1){
				//Cross-Domain設定
				_gaq.push(
					['_setAllowLinker', true],
					['_setAllowHash', false]
				);
				//Cross-Domain時とutm用Hashが混在している時のみ
				if (l.hash && l.hash.match(/(#|&)__utma=.+/) && l.href.match(/#utm_source=([^&]+)/)){
					//utm_sourceのみ残し（手抜き）
					var a = l.protocol+'//'+l.hostname+l.pathname+'#utm_source='+RegExp.$1;
					qd = 1;
					//Cross-Domain用Cookieを反映するためdummy計測してからRedirect
					_gaq.push(['_setAccount', 'UA-5312601-X'], ['_trackPageview'], function(){l.replace(a)});
				}
			}
		}
	};

	//即（JSロード後）に実行する関数
	var doAsapAfterJS = function(){
	};

	//DOM ready後に実行する関数
	var doAfterDom = function(){
		//離脱linkクリック時に
		jQuery('a[href]').filter(function(){
			var a = 0, d = internalDomains.split(',');
			for(i=0; i<d.length; i++){
				//現在のドメインと合致したら
				if (this.hostname.indexOf(d[i]) > -1) a++;
			}
			return this.hostname && this.hostname !== l.hostname && a==0;
		}).click(function(){
			//event計測
			_gaq.push(['_trackEvent', 'Exit Links', l.href, this.href]);
		});
	};

	//DOM ready後（JSロード後）に実行する関数
	var doAfterDomAfterJS = function(){

		//計測を実行
		doTrack();

		//Twitterボタンの計測
		try{
			twitterWidgets.onload = _ga.trackTwitter;
		}catch(e){}
	};

	//計測の処理
	var doTrack = function(){
		//対象外ドメインの時は計測しない
		if (!cd) return;

		//ここで各種カスタム変数をセット

		//Facebook経由の場合のみ
		if (l.search && l.search.indexOf('fb_ref=') > -1){
			//FBが付与するURLパラメータをSouceとContentに入れる
			_gaq.push(
				['_setCampSourceKey', 'fb_ref'],
				['_setCampContentKey', 'fb_source']
			);
			//さらに初回訪問時のみカスタム変数をセット
			if (document.cookie.indexOf('__utma=') == -1)
				_gaq.push(['_setCustomVar', 1, 'facebook', l.pathname, 1]);
		}

		//GAにSend
		_gaq.push(['_trackPageview'], ['_trackPageLoadTime']);

		//計測後の処理
		_gaq.push(function(){doAfterTrack();$LAB.script(f2);});
	};

	//計測後に実行する関数
	var doAfterTrack = function(){
		//Cross-DomainまたはCampaign用Hashがあれば削除
		if (l.hash && l.hash.match(/(#|&)(utm_source|__utma)=.+/))
			removeHash();

		//GAのCookieを読む
		gaUtil.init();

		//Cross-Domain LinkのURL変更
		jQuery('a[href]').filter(function(){
			return this.hostname && this.hostname !== l.hostname
		}).click(function(){
			var a = 0, d = internalDomains.split(',');
			for(i=0; i<d.length; i++){
				//現在のドメインと合致したら
				if (this.hostname.indexOf(d[i]) > -1) a++;
			}
			if (a > 0){
				var u = _gat._getTrackerByName()._getLinkerUrl(this.href, true);
				if (!this.target)
					l.href = u;
				else
					w.open(u);
				return false; //2011-08-04 位置を修正
			}
		});

		//Facebookいいね！ボタンの計測
		_ga.trackFacebook();

		//Debug用にCookie表示
		jQuery('#ga').html(
			'<b>Visits</b>='+gaUtil.visits
			+', <b>PV</b>='+gaUtil.pageviews
			+', <b>Campaign</b>='+gaUtil.campaign
			+', <b>Media</b>='+gaUtil.media
			+', <b>Source</b>='+gaUtil.source
		);

	};

	doAsap();
	$LAB.script(f0).wait(function(){
		if (qd) return;
		doAsapAfterJS();
		jQuery(document).ready(function(){
			doAfterDom();
			$LAB.script(f1).wait(function(){
				doAfterDomAfterJS();
			});
		});
	});

	//以下は汎用な関数

	//URLからhashを削除する関数
	var removeHash = function(){
		if ('replaceState' in history)
			history.replaceState('', document.title, window.location.pathname);
		else
			window.location.hash = '';
	};
};

// 非同期でJS filesをloadするのは既存libraryを流用
/*! LAB.js (LABjs :: Loading And Blocking JavaScript)
    v1.2.0 (c) Kyle Simpson
    MIT License
*/
(function(p){var q="string",w="head",L="body",M="script",u="readyState",j="preloaddone",x="loadtrigger",N="srcuri",E="preload",Z="complete",y="done",z="which",O="preserve",F="onreadystatechange",ba="onload",P="hasOwnProperty",bb="script/cache",Q="[object ",bw=Q+"Function]",bx=Q+"Array]",e=null,h=true,i=false,k=p.document,bc=p.location,bd=p.ActiveXObject,A=p.setTimeout,be=p.clearTimeout,R=function(a){return k.getElementsByTagName(a)},S=Object.prototype.toString,G=function(){},r={},T={},bf=/^[^?#]*\//.exec(bc.href)[0],bg=/^\w+\:\/\/\/?[^\/]+/.exec(bf)[0],by=R(M),bh=p.opera&&S.call(p.opera)==Q+"Opera]",bi=("MozAppearance"in k.documentElement.style),bj=(k.createElement(M).async===true),v={cache:!(bi||bh),order:bi||bh||bj,xhr:h,dupe:h,base:"",which:w};v[O]=i;v[E]=h;r[w]=k.head||R(w);r[L]=R(L);function B(a){return S.call(a)===bw}function U(a,b){var c=/^\w+\:\/\//,d;if(typeof a!=q)a="";if(typeof b!=q)b="";d=((/^\/\//.test(a))?bc.protocol:"")+a;d=(c.test(d)?"":b)+d;return((c.test(d)?"":(d.charAt(0)==="/"?bg:bf))+d)}function bz(a){return(U(a).indexOf(bg)===0)}function bA(a){var b,c=-1;while(b=by[++c]){if(typeof b.src==q&&a===U(b.src)&&b.type!==bb)return h}return i}function H(t,l){t=!(!t);if(l==e)l=v;var bk=i,C=t&&l[E],bl=C&&l.cache,I=C&&l.order,bm=C&&l.xhr,bB=l[O],bC=l.which,bD=l.base,bn=G,J=i,D,s=h,m={},K=[],V=e;C=bl||bm||I;function bo(a,b){if((a[u]&&a[u]!==Z&&a[u]!=="loaded")||b[y]){return i}a[ba]=a[F]=e;return h}function W(a,b,c){c=!(!c);if(!c&&!(bo(a,b)))return;b[y]=h;for(var d in m){if(m[P](d)&&!(m[d][y]))return}bk=h;bn()}function bp(a){if(B(a[x])){a[x]();a[x]=e}}function bE(a,b){if(!bo(a,b))return;b[j]=h;A(function(){r[b[z]].removeChild(a);bp(b)},0)}function bF(a,b){if(a[u]===4){a[F]=G;b[j]=h;A(function(){bp(b)},0)}}function X(b,c,d,g,f,n){var o=b[z];A(function(){if("item"in r[o]){if(!r[o][0]){A(arguments.callee,25);return}r[o]=r[o][0]}var a=k.createElement(M);if(typeof d==q)a.type=d;if(typeof g==q)a.charset=g;if(B(f)){a[ba]=a[F]=function(){f(a,b)};a.src=c;if(bj){a.async=i}}r[o].insertBefore(a,(o===w?r[o].firstChild:e));if(typeof n==q){a.text=n;W(a,b,h)}},0)}function bq(a,b,c,d){T[a[N]]=h;X(a,b,c,d,W)}function br(a,b,c,d){var g=arguments;if(s&&a[j]==e){a[j]=i;X(a,b,bb,d,bE)}else if(!s&&a[j]!=e&&!a[j]){a[x]=function(){br.apply(e,g)}}else if(!s){bq.apply(e,g)}}function bs(a,b,c,d){var g=arguments,f;if(s&&a[j]==e){a[j]=i;f=a.xhr=(bd?new bd("Microsoft.XMLHTTP"):new p.XMLHttpRequest());f[F]=function(){bF(f,a)};f.open("GET",b);f.send("")}else if(!s&&a[j]!=e&&!a[j]){a[x]=function(){bs.apply(e,g)}}else if(!s){T[a[N]]=h;X(a,b,c,d,e,a.xhr.responseText);a.xhr=e}}function bt(a){if(typeof a=="undefined"||!a)return;if(a.allowDup==e)a.allowDup=l.dupe;var b=a.src,c=a.type,d=a.charset,g=a.allowDup,f=U(b,bD),n,o=bz(f);if(typeof d!=q)d=e;g=!(!g);if(!g&&((T[f]!=e)||(s&&m[f])||bA(f))){if(m[f]!=e&&m[f][j]&&!m[f][y]&&o){W(e,m[f],h)}return}if(m[f]==e)m[f]={};n=m[f];if(n[z]==e)n[z]=bC;n[y]=i;n[N]=f;J=h;if(!I&&bm&&o)bs(n,f,c,d);else if(!I&&bl)br(n,f,c,d);else bq(n,f,c,d)}function Y(a){if(t&&!I)K.push(a);if(!t||C)a()}function bu(a){var b=[],c;for(c=-1;++c<a.length;){if(S.call(a[c])===bx)b=b.concat(bu(a[c]));else b[b.length]=a[c]}return b}D={script:function(){be(V);var a=bu(arguments),b=D,c;if(bB){for(c=-1;++c<a.length;){if(B(a[c]))a[c]=a[c]();if(c===0){Y(function(){bt((typeof a[0]==q)?{src:a[0]}:a[0])})}else b=b.script(a[c]);b=b.wait()}}else{for(c=-1;++c<a.length;){if(B(a[c]))a[c]=a[c]()}Y(function(){for(c=-1;++c<a.length;){bt((typeof a[c]==q)?{src:a[c]}:a[c])}})}V=A(function(){s=i},5);return b},wait:function(a){be(V);s=i;if(!B(a))a=G;var b=H(t||J,l),c=b.trigger,d=function(){try{a()}catch(err){}c()};delete b.trigger;var g=function(){if(J&&!bk)bn=d;else d()};if(t&&!J)K.push(g);else Y(g);return b}};if(t){D.trigger=function(){var a,b=-1;while(a=K[++b])a();K=[]}}else D.trigger=G;return D}function bv(a){var b,c={},d={"UseCachePreload":"cache","UseLocalXHR":"xhr","UsePreloading":E,"AlwaysPreserveOrder":O,"AllowDuplicates":"dupe"},g={"AppendTo":z,"BasePath":"base"};for(b in d)g[b]=d[b];c.order=!(!v.order);for(b in g){if(g[P](b)&&v[g[b]]!=e)c[g[b]]=(a[b]!=e)?a[b]:v[g[b]]}for(b in d){if(d[P](b))c[d[b]]=!(!c[d[b]])}if(!c[E])c.cache=c.order=c.xhr=i;c.which=(c.which===w||c.which===L)?c.which:w;return c}p.$LAB={setGlobalDefaults:function(a){v=bv(a)},setOptions:function(a){return H(i,bv(a))},script:function(){return H().script.apply(e,arguments)},wait:function(){return H().wait.apply(e,arguments)}};(function(a,b,c){if(k[u]==e&&k[a]){k[u]="loading";k[a](b,c=function(){k.removeEventListener(b,c,i);k[u]=Z},i)}})("addEventListener","DOMContentLoaded")})(window);

ulTracker();
