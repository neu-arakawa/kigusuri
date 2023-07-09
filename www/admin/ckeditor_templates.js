CKEDITOR.addTemplates("default", {
    imagesPath:CKEDITOR.getUrl(CKEDITOR.plugins.getPath("templates")+"templates/images/"),
    templates:[
        {
            title:"テスト",
            image:"template3.gif",
            html:'<p>下記の内容で出展します。</p><p>是非ご来場ください。</p><table class="exhibitions-tbl tbl01"><tbody><tr><th>会期</th><td>2016年2月24日～2月26日</td></tr><tr><th>会場</th><td>インテックス大阪</td></tr><tr><th>主催</th><td>テスト株式会社</td></tr><tr><th>ブース番号</th><td>10</td></tr><tr><th>URL</th><td><a href="http://google.com" target="_blank">http://google.com</a></td></tr></tbody></table>'
        }
    ]
});