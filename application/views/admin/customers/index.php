<!DOCTYPE html>
<html>
<head>
	<title>CURD Codeigniter Jquery Ajax</title>
	<!-- Latest compiled and minified CSS & JS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.20/datatables.min.css"/>
	<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.20/datatables.min.js"></script>
</head>
<body>
	<div class="container">
		<h1 style="font-size:20pt">Ajax CRUD with Bootstrap modals and Datatables</h1>

		<h3>Person Data</h3>
		<br />
		<button class="btn btn-success" onclick="add_person()"><i class="glyphicon glyphicon-plus"></i> Add Person</button>
		<button class="btn btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Reload</button>
		<br />
		<br />
		<table class="table table-striped table-bordered" cellspacing="0" width="100%" id="table">
			<thead>
				<tr>
					<th>ID</th>
					<th>Name</th>
					<th>Email</th>
					<th>Phone</th>
					<th>Address</th>
					<th>Amount</th>
					<th>Created_at</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<!-- <tr>
					<td></td>
				</tr> -->
			</tbody>
		</table>
		<div class="modal fade" id="modal_form">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title">Add Customer</h4>
					</div>
					<div class="modal-body">
						<form action="#" method="POST" id="form" role="form">
							<input type="hidden" name="id">
							<div class="form-group">
								<label for="name">Name</label>
								<input type="text" class="form-control" id="Name" name="name" placeholder="Your Name">
							</div>
							<div class="form-group">
								<label for="email">Email</label>
								<input type="text" class="form-control" id="email" name="email" placeholder="Your Email">
							</div>
							<div class="form-group">
								<label for="phone">Phone</label>
								<input type="text" class="form-control" id="phone" name="phone" placeholder="Your Phone">
							</div>
							<div class="form-group">
								<label for="phone">Password</label>
								<input type="password" class="form-control" id="password" name="password" placeholder="Your Password">
							</div>
							<div class="form-group">
								<label for="address">Address</label>
								<input type="text" class="form-control" id="address" name="address" placeholder="Your Address">
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="submit" id="btnSave" onclick="save()" class="btn btn-primary">Save changes</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
<script type="text/javascript">
	let save_method;
	let table;
	let base_url = '<?php echo base_url(); ?>';
	jQuery(document).ready(function($) {
		// datatables
		table = $('#table').DataTable({ 

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "retrieve"  : true,
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo base_url('customers/ajax_list'); ?>",
            "type": "POST"
        },

        //Set column definition initialisation properties.
        "columnDefs": [
        { 
                "targets": [ -1 ], //last column
                "orderable": false, //set not orderable
            },
            { 
                "targets": [ -2 ], //2 last column (photo)
                "orderable": false, //set not orderable
            },
            ],

        });
	});
	function reload_table() {
    	table.ajax.reload(null, false); // reload datatable ajax
    }
   	function add_person() {
   		save_method = 'add';
   		$('#form')[0].reset(); // reset form on modals
   		$('.form-group').removeClass('has-error'); // clear error class
   		$('.text-danger').css('display', 'none');
   		$('.help-block').empty(); // clear error string
   		$('#modal_form').modal('show'); // show modals bootstrap
   		$('.modal-title').text('Add Person');

   	}
   	function edit_cus(id) {
   		save_method = 'update';
   		$('#form')[0].reset(); // reset form on modals
   		$('.text-danger').css('display', 'none');
   		$.ajax({
   			url: '<?php echo base_url('Customers/ajax_exit/') ?>'+ id,
   			type: 'GET',
   			dataType: 'JSON',
   			success: function(data) {
   				$('[name="id"]').val(data.id);
   				$('[name="name"]').val(data.name);
   				$('[name="email"]').val(data.email);
   				$('[name="phone"]').val(data.phone);
   				$('[name="address"]').val(data.address);
   				$('#modal_form').modal('show'); // show bootstrap modal
   				$('.modal-title').text('Sửa thông tin người dùng');
   			},
   			error: function(jqXHR, textStatus, errorThrown) {
   				alert('Error get data from ajax');
   			}
   		})
   		.done(function() {
   			console.log("success");
   		})
   		.fail(function() {
   			console.log("error");
   		})
   		.always(function() {
   			console.log("complete");
   		});
   		
   	}
   	function save() {
   		$('#btnSave').text('saving...'); // change button text
   		$('#btnSave').attr('disabled', true); // set button disabled
   		let url;
   		if (save_method == 'add') {
   			url = "<?php echo base_url('Customers/ajax_add') ?>";
   		}
   		else {
   			url = "<?php echo base_url('Customers/ajax_update') ?>";
   		}
   		// Ajax adding data to database
   		$.ajax({
   			url: url,
   			type: 'POST',
   			dataType: 'JSON',
   			data: $('#form').serialize(),
   			success: function(data) {
   				if (data.status_nofi) {
   					$('#modal_form').modal('hide');
   					reload_table();
   				}
   				else {
   					$.each(data.messages, function(i, val) {
   						 let element = $('[name = "' + i + '"]');
   						 element.closest('div.form-group')
   						 .addClass(val.length > 0 ? 'has-error' : '')
   						 .find('.text-danger').remove();
   						 element.after(val);
   					});
   				}
   				$('#btnSave').text('save'); // change button text
   				$('#btnSave').attr('disabled', false); //set button enable
   			},
   			error: function(jqXHR, textStatus, errorThrown) {
   				console.log(jqXHR);
   				console.log(textStatus);
   				console.log(errorThrown);
   				$('#btnSave').text('save');
   				$('#btnSave').attr('disabled', false);
   			}
   		})
   		.done(function() {
   			console.log("success");
   		})
   		.fail(function() {
   			console.log("error");
   		})
   		.always(function() {
   			console.log("complete");
   		});
   		
   	}
   	function delete_cus(id) {
   		if (confirm('Bạn có chắc muốn xóa bản ghi này không ?')) {
   			// ajax delete data to databse
   			$.ajax({
   				url: '<?php echo base_url('Customers/ajax_delete/') ?>'+id,
   				type: 'POST',
   				dataType: 'JSON',
   				success: function(data) {
   					// if success reload ajax table
   					$('#modal_form').modal('hide');
   					reload_table();
   				},
   				error: function (jqXHR, textStatus, errorThrown) {
   					alert('Xóa không thành công !');
   				}
   			})
   			.done(function() {
   				console.log("success");
   			})
   			.fail(function() {
   				console.log("error");
   			})
   			.always(function() {
   				console.log("complete");
   			});
   			
   		}
   	}
</script>
</html>