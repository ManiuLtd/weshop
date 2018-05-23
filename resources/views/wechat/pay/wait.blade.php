<!doctype html>
<html lang="zh-CN">
  <head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>收银台</title>
    <link rel="stylesheet" href ="{{asset("assets/css/reset.css")}}" >
    <link rel="stylesheet" href =" {{asset("assets/font/iconfont.css")}}">
    <script src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
    <script src="https://cdn.bootcss.com/vue/2.5.16/vue.min.js"></script>
    <script src="https://cdn.bootcss.com/axios/0.18.0/axios.min.js"></script>
    <style media="screen">
    html,body{padding:0;margin:0;font-size:50px;}
    .pay{font-size:.32rem}
      .pay .item{display: flex;display: -webkit-flex;width: 100%; background-color: #fff;height: 1.6rem;padding: .3rem .4rem;}
      .pay .item .pay-t{width: 60%;line-height: 1rem;}
      .pay .item .pay-t i{font-size: .4rem;font-weight: bold;}
      .pay .item .pay-limit{width: 40%; line-height: .4rem;font-size: .28rem;text-align: right;color: #888;}
      .group{margin-top: .4rem;}
      .group .item {display: flex;display: -webkit-flex;height: 1.3rem;padding: .25rem .4rem;line-height: .8rem;border-bottom: 1px solid #eee;}
      .group .item .icon{ width: .7rem; font-size: .5rem;text-align: center;}
      .group .item .pay-title{flex: 1;-webkit-flex:1;line-height: .4rem;}
      .group .item .pay-title p.gray{font-size: .28rem; color: #888;}
      .group .item .pay-title p.title{font-size: .32rem;font-weight: bold;}
      .footer{width:100%; position: absolute;bottom: 0;height: 1.2rem;line-height: 1.2rem;background-color: #00b945;}
      .footer span{display: block;width: 100%;text-align: center;font-weight: bold;color: #fff;letter-spacing: 2px;font-size: .34rem;}
      .footer span a{color: #fff;}
      .on .icon-zhuanzhanghuikuan{color: #3bb6ff;}
      .on .icon-huipiao{color: #c273ff;}
      .on .icon-gou{color: #3db858;}
    </style>
    <script type="text/javascript">
    //调用微信JS api 支付
    function jsApiCall()
    {
      WeixinJSBridge.invoke(
	"getBrandWCPayRequest",
	{!! $json !!},
	function(res){
	  WeixinJSBridge.log(res.err_msg);
	  alert(res.err_code+res.err_desc+res.err_msg);
	}
      );
    }

    function callpay()
    {
      if (typeof WeixinJSBridge == "undefined"){
	if( document.addEventListener ){
	  document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
	}else if (document.attachEvent){
	  document.attachEvent('WeixinJSBridgeReady', jsApiCall);
	  document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
	}
      }else{
	jsApiCall();
      }
    }
    </script>
  </head>
  <body style="background-color:#f0f1f0;">
    <div class="container" id="app">
  <div class="pay">
    <div class="item">
      <div class="pay-t">
        <span>需支付：<i class="y">￥{{ $order->payment->pay  }}</i></span>
      </div>
      <div class="pay-limit">
        <p>剩余支付时间</p>
        <p>@{{ expire }}</p>
      </div>
    </div>
    <div class="group">
      <div v-for="item in items">
        <div class="item" v-on:click="setChannel(item.id)" v-bind:class="item.id==active?'on':''" >
          <div class="icon" v-if="item.id==2">
            <i class="iconfont icon-weixinzhifu"></i>
          </div>
          <div class="icon" v-if="item.id==1">
            <i class="iconfont icon-zhuanzhanghuikuan"></i>
          </div>
          <div class="icon" v-if="item.id==3">
            <i class="iconfont icon-weixinzhifu"></i>
          </div>
          <div class="pay-title" v-if="item.id==2">
            <p class="title">微信支付</p>
            <p class="gray">微信安全支付</p>
          </div>
          <div class="pay-title" v-if="item.id==1">
            <p class="title">对公转账</p>
            <p class="gray">公司对公转账支付</p>
          </div>
          <div class="pay-title" v-if="item.id==3">
            <p class="title">货到付款</p>
            <p class="gray">收货后支付货款</p>
          </div>
          <div class="icon">
            <i class="iconfont icon-gou"></i>
          </div>
        </div>
        <div class="item"  v-if="item.id==1" v-on:click="setChannel(0)" v-bind:class="active==0?'on':''" >
          <div class="icon">
            <i class="iconfont icon-huipiao"></i>
          </div>

          <div class="pay-title">
            <p class="title">银行汇票</p>
            <p class="gray">银行邮寄汇票支付</p>
          </div>

          <div class="icon">
            <i class="iconfont icon-gou"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="footer">
    <span v-if="active==2"  onclick="callpay()">确认支付</span>
    <span v-if="active==0"><a href="{{route("wechat.pay.offline")}}/?order_id={{ $order->id }}&&type=0">确认</a></span>
    <span v-if="active==1"><a href="{{route("wechat.pay.offline")}}/?order_id={{ $order->id }}&&type=1">确认</a></span>
    <span v-if="active==3"><a href="{{route("wechat.pay.offline")}}/?order_id={{ $order->id }}&&type=3">确认</a></span>
  </div>
</div>
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
  <script type="text/javascript">
    new Vue({
      el: '#app',
      data: {
        items: [{
            id : 1,
            name : "offline"
          },
          {
            id : 2,
            name : "wechat"
          },
          {
            id : 3,
            name : "wechat"
          }
        ],
        active: 1,
        expire:""
      },
      mounted () {
        countdown(this)
      },
      methods: {
        setChannel:function(index){
          this.active = index;
        }
      }

    })
    function countdown(_this){
      var order_expire ={{ strtotime($order->expire) }} ;
      var expire = 0;
      time = setInterval(function () {
        timestamp =parseInt(new Date().getTime()/1000);
        expire = order_expire - timestamp;
        _this.expire = init(expire);
      }, 1000);
    }
    function init(mss){
      if(mss<=1){
        clearInterval(time);
        //订单超时
      }
      var days = parseInt(mss / (60 * 60 * 24))?parseInt(mss / (60 * 60 * 24))+"天 ":"";
      var hours = parseInt((mss % ( 60 * 60 * 24)) / (60 * 60))?parseInt((mss % ( 60 * 60 * 24)) / (60 * 60))+"时 ":"";
      var minutes = parseInt((mss % (60 * 60)) / 60);
      var seconds = mss % 60;
      return days + hours + minutes + "分 " + seconds + "秒 ";
    }
  </script>
  </body>
</html>
