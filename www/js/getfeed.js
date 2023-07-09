/* getFeed.js */

google.load("feeds", "1");

function initialize() {
  var control = new google.feeds.FeedControl();
  var feedurl1 = "http://www.kigusuri.com/shop/tochimoto/topic/feeds/rss2.0.xml";
  var feedurl2 = "http://www.kigusuri.com/shop/tochimoto-ph/topic/feeds/rss2.0.xml";

  control.addFeed(feedurl1, "�Ȗ{�V�C����� ��X");
  control.addFeed(feedurl2, "�Ȗ{�V�C����� �����X");

  control.draw(document.getElementById("feed"));
}

google.setOnLoadCallback(initialize);
