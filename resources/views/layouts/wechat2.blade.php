<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="UTF-8">
    <title>{{ $title ?? "weshop" }}</title>
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <link rel="stylesheet" href="{{ asset("assets/css/reset.css") }}">
    <link rel="stylesheet" href="{{ asset("assets/css/style.css") }}">
    <link rel="stylesheet" href="{{ asset("assets/font/iconfont.css") }}">
    <script type="text/javascript">
      (function(doc, win) {
        var docEl = doc.documentElement,
          resizeEvt = 'orientationchange' in window ? 'orientationchange' : 'resize',
          recalc = function() {
            var clientWidth = docEl.clientWidth;
            if(!clientWidth) return;
            docEl.style.fontSize = (clientWidth / 7.5) + 'px';
          };
        if(!doc.addEventListener) return;
        win.addEventListener(resizeEvt, recalc, false);
        doc.addEventListener('DOMContentLoaded', recalc, false);
      })(document, window);
    </script>
    @yield("style")
  </head>
  <body>
    <!-- <nav>
      
      <a href="{{ route("wechat.logout") }}">logout</a>
    </nav> -->
    @yield("content")
    <script src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
    <script src="https://cdn.bootcss.com/vue/2.5.16/vue.min.js"></script>
    <script src="https://cdn.bootcss.com/axios/0.18.0/axios.min.js"></script>
    <script>
    wx.config({!! app("wechat.official_account")->jssdk->buildConfig($interfaces ?? [], true) !!});
    wx.ready(function () {
      wx.onMenuShareTimeline({
	title: "{{ $page_title ?? "分享title" }}",
	link: "{{ url()->current() . "?rec=" . auth()->user()->rec_code }}",
	imgUrl: "https://pic1.zhimg.com/v2-c320644d354158004e6fc91d539d0529_im.jpg",
	success: function () {
	  alert("分享成功");
	},
	cancel: function () {
	  alert("取消了");
	}
      });
    });
    </script>
    @yield("script")
  </body>
</html>
