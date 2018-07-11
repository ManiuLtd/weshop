@extends("layouts.admin")

@section("content")

  <div class="box">
    <div class="box-header">
      <h3 class="box-title">公众号—消息推送</h3>
      <h3 class="box-title">
	<a href="{{ route("admin.marketing.create",['limit' => $limit, 'name' => $name]) }}">添加</a>
      </h3>
    </div>
    <div class="box-body">
      <div class="dataTables_wrapper form-inline dt-bootstrap">
	<div class="row">
	  <div class="col-sm-6">
	    <div class="dataTables_length">
	      <form action="{{ url()->current() }}" id="form-start">
		<label>
		  每页
		  <select name="limit" class="form-control input-sm">
		    <option value="10" {{ $limit == 10 ? 'selected':'' }}>10</option>
			<option value="25" {{ $limit == 25 ? 'selected':'' }}>25</option>
			<option value="50" {{ $limit == 50 ? 'selected':'' }}>50</option>
			<option value="100" {{ $limit == 100 ? 'selected':'' }}>100</option>
		  </select>
		  条
		</label>
		<label>
		  <input class="form-control input-sm" name="name" value="{{ $name }}" placeholder="" type="search">
		  <input type="hidden" name="active" value="{{ $active }}" id="active">
		  <button>搜索</button>
		</label>
	      </form>
	    </div>
	  </div>
	</div>
	<div class="row">
	  <div class="col-sm-12 table-responsive">
	    <table class="table table-bordered table-striped dataTable table-hover table-condensed" role="grid">
	      <thead>
		<tr>
		  <th>序号</th>
		  <th>标题</th>
		  <th>业务类型</th>
		  <th>变更结果</th>
		  <th>时间</th>
		  <th>结尾声明</th>
		  <th>链接</th>
		  <th>用户分类</th>
		  <th>操作</th>
		</tr>
	      </thead>
	      <tbody>
		@foreach ($Marketing as $item)
		  <tr role="row">
		    <td>{{ $serial++ }}</td>
		    <td>{{ $item->title }}</td>
		    <td>{{ $item->text_type }}</td>
		    <td>{{ $item->result }}</td>
		    <td>{{ $item->created_at }}</td>
		    <td>{{ $item->ending }}</td>
		    <td>{{ $item->link }}</td>
		    <td>{{ $item->user_type }}</td>
		    <td>
		      <a href="{{ route("admin.marketing.edit", ['item' => $item]) }}">编辑</a>
		      &nbsp;|&nbsp;
		      <a href="{{ route("admin.marketing.show", $item) }}">详细</a>
		    </td>
		  </tr>
		@endforeach
	      </tbody>
	    </table>
	  </div>
	</div>

      </div>
    </div>
    <div class="box-footer">
      <div class="row">
		  <div class="col-sm-6">{{ $Marketing->appends(["limit" => $limit, "name" => $name,'active' => $active])->links() }}<div style="height:100%;lone-height:100%"> 总共：{{ $line_num }}</div></div>
      </div>
    </div>
  </div>
  <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdn.bootcss.com/layer/3.1.0/layer.js"></script>
  <script>
  var number=0;
  function stock(obj){
    $("#number"+obj).attr("disabled", false);
    number = $("#number"+obj).val();
  }
  var fprice=0;
  function myprice(obj){
    $("#price"+obj).attr("disabled", false);
    fprice = $("#price"+obj).val();
  }
  function selstr(obj)
  {
      if(obj){
          $('#active').val(1);
          $('#form-start').submit();
	  }else{
          $('#active').val(0);
          $('#form-start').submit();
	  }

  }

  //修改库存
  function onclnum(obj,item)
  {
    var number = $('#number'+obj).val();
    var onurl = "{{ route('admin.product.modifying') }}";
    var data = {id:obj,number:number,item:JSON.stringify(item)};
    axios.post(onurl,data)
      .then(function (res) {
	if(res.status == 200){
          var p = res.data;
          if(p.status == 'ok'){
          }else{
            $('#number'+obj).val(number);
          }
	}else{
          $('#number'+obj).val(number);
	}
        $("#number"+obj).attr("disabled", true);
      })
  }
  //修改价格
  function onprice(obj,un_price)
  {
    var price = $('#price'+obj).val();//吨价
    var onurl = "{{ route('admin.product.modifying') }}";
    var data = {id:obj,price:price,un_price:un_price};
    axios.post(onurl,data)
      .then(function (res) {
        if(res.status == 200){
          var p = res.data;
          if(p.status == 'ok'){
            $('#un'+obj).text(p.un_price);
          }else{
            $('#price'+obj).val(fprice);
          }
        }else{
          $('#price'+obj).val(fprice);
        }
        $("#price"+obj).attr("disabled", true);
      })
  }
  </script>
@endsection
