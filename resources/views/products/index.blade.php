@extends('layouts.master')

@section('title', 'Products')

@section('content')
	<div class="row" style="margin-top: 50px;">
		@if (count($errors) > 0)
			<div class="alert alert-danger">
				<ul>
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif
	
		<!--form class="form-horizontal" -->
		{!! Form::open(array('url' => 'products', 'method'=>'POST', 'class' => 'form-horizontal')) !!}
		
		  <div class="form-group">
			<label for="name" class="col-sm-2 control-label">Product Name</label>
			<div class="col-sm-10">
				<!--input type="text" class="form-control" name="name" id="name" placeholder="Product Name"-->
				{!! Form::text('name', null, ['class' => 'form-control']) !!}
				{!! $errors->first('name', '<div class="text-danger">:message</div>') !!}
			</div>
		  </div>
		  <div class="form-group">
			<label for="quantity" class="col-sm-2 control-label">Quantity</label>
			<div class="col-sm-10">
				<!--input type="text" class="form-control" name="quantity" id="quantity" placeholder="Quantity"-->
				{!! Form::text('quantity', null, ['class' => 'form-control']) !!}
				{!! $errors->first('quantity', '<div class="text-danger">:message</div>') !!}
			</div>
		  </div>
		  <div class="form-group">
			<label for="text" class="col-sm-2 control-label">Price</label>
			<div class="col-sm-10">
				<!--input type="text" class="form-control" name="price" id="price" placeholder="Price"-->
				{!! Form::text('price', null, ['class' => 'form-control']) !!}
				{!! $errors->first('price', '<div class="text-danger">:message</div>') !!}
			</div>
		  </div>
		  <div class="form-group" >
			<div class="col-sm-offset-2 col-sm-10" style="margin-top: 20px;">
			  <button type="submit" class="btn btn-default">Add</button>
			</div>
		  </div>
		<!--/form-->
		{!! Form::close() !!}
	</div>
	
	<div class="row" style="margin-top: 20px;">
		<div class="table-responsive">
		  <table class="table table-bordered">
			<thead>
			  <tr>
				<th>#</th>
				<th>Product name</th>
				<th>Quantity</th>
				<th>Price</th>
				<th>Created Date</th>
				<th>Actions</th>
			  </tr>
			</thead>
			<tbody id="products">
				@foreach($datas['products'] as $key=>$data)
				  <tr id='product{{ $data["id"] }}'>
					<!--th scope="row">{{$key+1}}</th-->
					<th scope="row"><span class="prodid">{{$key}}</span></th>
					<td>
						<!--span>{{$data["name"]}}<span-->
						{!! Form::text('name', $data["name"], ['class' => 'form-control', 'readonly']) !!}
						{!! $errors->first('name', '<div class="text-danger">:message</div>') !!}
					</td>
					<td>
						<!--span>{{$data["quantity"]}}<span-->
						{!! Form::text('quantity', $data["quantity"], ['class' => 'form-control', 'readonly']) !!}
						{!! $errors->first('quantity', '<div class="text-danger">:message</div>') !!}
					</td>
					<td>
						<!--span>$ {{$data["price"]}}<span-->
						{!! Form::text('price', $data["price"], ['class' => 'form-control', 'readonly']) !!}
						{!! $errors->first('price', '<div class="text-danger">:message</div>') !!}
					</td>
					<td>{{$data["created"]}}</td>
					<td><a class="edit" href="javascript:void(0);">Edit</a> | <a class="delete" href="javascript:void(0);">Delete</a></td>
				  </tr>
				@endforeach
			</tbody>
		  </table>
		  
		  <p> Total value number: $ <span id="totalValueNumber">{{ $totalPrice }}</span> </p>
		</div>
	</div>	
	
	<script type="text/javascript">
	$(document).ready( function(){
		$.ajaxSetup({
		   headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
		});
		
		$('#products').on('click', '.edit', function(){
			$(this).parent().parent().find('input').removeAttr('readonly');
			$(this).html('Update');
			$(this).removeClass('edit').addClass('save');
			$(this).parent().parent().find('input[name=name]').select();
		});

		$('#products').on('click', '.save', function(){
			var ts = $(this);
			var pr = $(this).parent().parent();
			var name = pr.find('input[name="name"]').val();
			var quantity = parseInt(pr.find('input[name="quantity"]').val());
			var price = parseInt(pr.find('input[name="price"]').val());
			var prodid = parseInt(pr.find('span.prodid').html());
			$.ajax({
				method: "PUT",
				url: "products/"+prodid,
				data: { name: name, quantity: quantity, price: price }
			}).done(function( msg ) {
				if(msg.status) {
					pr.find('input.form-control').each(function() {
						$(this).attr('readonly', 'readonly');
						$(this).removeClass('save').addClass('edit');
					});
					updateTtPrice();
					ts.html('Edit');
				}
				alert(msg.message);
			});
		});
		
		$('#products').on('click', '.delete', function(){
			if (window.confirm("Are you sure?")) {
				var pr = $(this).parent().parent();
				var prodid = parseInt(pr.find('span.prodid').html());
				$.ajax({
					method: "DELETE",
					url: "products/"+prodid
				}).done(function( msg ) {
					if(msg.status) {
						pr.find('input.form-control').each(function() {
							pr.remove();;
						});
						updateTtPrice();
					}
					alert(msg.message);
				});
			}
		});
	});
	
	function updateTtPrice() {
		var ttPrice = 0;
		$('#products tr').each(function() {
			var quantity = parseInt($(this).find('input[name="quantity"]').val());
			var price = parseInt($(this).find('input[name="price"]').val());
			ttPrice += quantity*price;
		});
		$("#totalValueNumber").html(ttPrice);
	}
	
	</script>
@endsection
