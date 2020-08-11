<!DOCTYPE html>
<html>
<head>
	<title><?php echo $page_title ?></title>
	<!-- Latest compiled and minified CSS & JS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
</head>
<body>
	<div class="container">
		<h2><?php echo $head_title; ?></h2> </hr>
		<div class="row">
			<div class="col-md-8">
				<?php foreach ($list_product as $key) { ?>
				<div class="thumbnail">
					<img width="200" src="<?php echo base_url(); ?>upload/images/<?php echo $key['product_image'] ?>">
					<div class="caption">
						<h4>Tên sản phẩm: <?php echo $key['product_name']; ?></h4>
						<div class="row">
							<div class="col-md-7">
								<h4>Giá sản phẩm: <?php echo number_format($key['product_price']); ?>$</h4>
							</div>
							<div class="col-md-5">
								<input type="number" name="quantity" id="product_id" value="1" min = "1" class="quantity form-control">
							</div>
						</div>
						<button class="add_cart btn btn-success btn-block" data-productid="<?php echo $key['product_id'] ?>" data-productname="<?php echo $key['product_name'] ?>" data-productprice="<?php echo $key['product_price'] ?>">Thêm vào giỏ hàng</button>
					</div>
				</div>
			<?php } ?>
			</div>
			<div class="col-md-4">
				<h4>Giỏ hàng</h4>
				<table class="table table-striped">
					<thead>
						<tr>
							<th>Sản phẩm</th>
							<th>giá</th>
							<th>sl</th>
							<th>Tổng</th>
							<th>h/d</th>
						</tr>
					</thead>
					<tbody id="detail_cart">
						
					</tbody>
				</table>
			</div>
		</div>


	</div>
</body>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$('.add_cart').click(function(event) {
			let product_id		= $(this).data("productid");
			let product_name 	= $(this).data("productname");
			let product_price	= $(this).data("productprice");
			let quantity 		= $('#product_id').val();

			$.ajax({
				url: '<?php echo base_url(); ?>product/add_to_cart',
				type: 'POST',
				data: {product_id: product_id, product_name: product_name, product_price: product_price, quantity: quantity},
				success: function(data) {
					$('#detail_cart').html(data);
					window.alert('Thêm vào giỏ hàng thành công!');
				}
			});
			
		});
		$('#detail_cart').load("<?php echo base_url('product/load_cart');?>");
		// delete sp
		$(document).on('click','.remove_cart',function(){
			if (confirm("Bạn có chắc muốn xóa sản phầm này ra khỏi giỏ hàng không?")) {
				let row_id = $(this).attr("id"); 
			$.ajax({
				url : "<?php echo base_url('product/delete_cart');?>",
				method : "POST",
				data : {row_id : row_id},
				success :function(data){
					$('#detail_cart').html(data);
				}
			});
			}
			
		});
		
	});
</script>
</html>