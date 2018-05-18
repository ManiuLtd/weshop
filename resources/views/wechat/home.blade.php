@extends("layouts.wechat")

@section("content")

    <div class="container">
      <div class="user">

	      <div class="grid">
	        <div class="item ord">
	          <a href="{{ route("wechat.order.index") }}">
							<div class="icon">
	              <img src="{{ asset("assets/img/order.png") }}" />
	            </div>
		          <div class="txt"><span>全部订单</span></div>
	          </a>
	        </div>
	       <div class="item">
            <a href="##">
            <div class="icon">
              <i class="iconfont icon-fapiao"></i>
            </div>
            <div class="txt"><span>申请开票</span></div>

            </a>
          </div>
          <div class="item">
            <a href="{{ route("wechat.home.coupon") }}">
            <div class="icon">
              <i class="iconfont icon-youhuiquan"></i>
            </div>
            <div class="txt"><span>优惠券</span></div>

            </a>
          </div>
          <div class="item">
            <a href="##">
            <div class="icon">
              <i class="iconfont icon-tuihuanhuo"></i>
            </div>
            <div class="txt"><span>退换货</span></div>

            </a>
          </div>

	      </div>
	      <div class="grid">
          <div class="item">
            <a href="{{ route("wechat.home.product_star") }}">
            <div class="icon">
              <i class="iconfont icon-wodeshoucang"></i>
            </div>
            <div class="txt"><span>我的收藏</span></div>
            <!--<div class="icon">
              <i class="iconfont icon-jinru"></i>
            </div>-->
            </a>
          </div>
           <div class="item">
            <a href="{{ route("wechat.home.waiter") }}">
            <div class="icon">
              <i class="iconfont icon-kefu"></i>
            </div>
            <div class="txt"><span>联系客服</span></div>
            </a>
          </div>
        </div>

	    </div>

	  </div>
@endsection
