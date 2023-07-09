if (document.referrer.indexOf('kigusuri.com') == -1
&& ((navigator.userAgent.indexOf('iPhone') > 0
&& navigator.userAgent.indexOf('iPad') == -1)
|| navigator.userAgent.indexOf('iPod') > 0
|| navigator.userAgent.indexOf('Android.*Mobile') > 0))
{         location.href = 'http://www.kigusuri.com/sp/';
}
