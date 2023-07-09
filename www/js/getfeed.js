/* getFeed.js */

google.load("feeds", "1");

function initialize() {
  var control = new google.feeds.FeedControl();
  var feedurl1 = "http://www.kigusuri.com/shop/tochimoto/topic/feeds/rss2.0.xml";
  var feedurl2 = "http://www.kigusuri.com/shop/tochimoto-ph/topic/feeds/rss2.0.xml";

  control.addFeed(feedurl1, "“È–{“VŠC“°–ò‹Ç î’¬“X");
  control.addFeed(feedurl2, "“È–{“VŠC“°–ò‹Ç •Ÿ“‡“X");

  control.draw(document.getElementById("feed"));
}

google.setOnLoadCallback(initialize);
