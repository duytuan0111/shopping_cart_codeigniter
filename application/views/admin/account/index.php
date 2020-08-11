<?php require_once APPPATH.'/views/admin/_header.php' ?>
<?php require_once APPPATH.'/views/admin/_sidebar.php' ?>
<style type="text/css">
  .removeRow {
    background-color: #FF0000 !important;
    color: #FFFFFF !important;
  }
</style>
<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3><?php echo $head_title; ?></h3>
      </div>

      <div class="title_right">
        <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
          <button class="btn btn-success" onclick="add_acc()"><i class="glyphicon glyphicon-plus"></i> Thêm mới</button>
          <button class="btn btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Tải lại</button>
        </div>
      </div>
    </div>
    
    <div class="clearfix"></div>

    <div class="row">
      <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
          <div class="x_content">
            <div class="row">
              <div class="col-sm-12">
                <div class="card-box table-responsive">
                  <table id="table" class="table table-striped table-bordered bulk_action" style="width:100%">
                    <thead>
                      <tr>
                       <th><button class="btn btn-danger" name="delete_all" id="delete_all">Xóa theo danh sách</button></th>
                       <th>ID</th>
                       <th>NAME</th>
                       <th>USERNAME</th>
                       <th>LAST LOGIN</th>
                       <th class="text-center">Action</th>
                     </tr>
                   </thead>


                  <tbody>
                  
                 </tbody>
               </table>
               <!-- modal add account -->
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
                          <label for="email">Username</label>
                          <input type="text" class="form-control" id="username" name="username" placeholder="Your Username">
                        </div>
                        <div class="form-group">
                          <label for="phone">Password</label>
                          <input type="password" class="form-control" id="password" name="password" placeholder="Your Password">
                        </div>
                        <div class="form-group">
                          <label for="phone">Password Confirm</label>
                          <input type="password" class="form-control" id="password_confirm" name="password-confirm" placeholder="Password Confirm ...">
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
           </div>
         </div>
       </div>
     </div>
   </div>
 </div>
</div>
</div>
<!-- /page content -->

<?php require_once APPPATH.'/views/admin/_footer.php' ?>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.21/datatables.min.js"></script>
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
        "searching" : false,
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo base_url('admin/account/ajax_list'); ?>",
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

      //
      $('.delete_checkbox').click(function(event) {
        if ($(this).is(':checked')) {
          $(this).closest('tr').addClass('removeRow');
        }
        else {
          $(this).closest('tr').removeClass('removeRow');
        }
      }); 

      $('#delete_all').click(function(event) {
        let checkbox = $('.delete_checkbox:checked');
        if (checkbox.length > 0) {
          let checkbox_value = [];
          $(checkbox).each(function(index, el) {
              checkbox_value.push($(this).val());   
          });
          $.ajax({
            url: '<?php echo base_url(); ?>admin/account/delete_all',
            type: 'POST',
            data: {checkbox_value: checkbox_value},
            success: function() {
              $('.removeRow').fadeOut(1500);
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
        else {
          window.alert('Vui lòng chọn bản ghi cần xóa !');
        }
      });
   });

    function reload_table() {
      table.ajax.reload(null, false); // reaload datatable ajax
    }

    function add_acc() {
      save_method = 'add';
      $('#form')[0].reset(); // reset form on modals
      $('.form-group').removeClass('has-error'); // clear error class
      $('.text-danger').css('display', 'none'); 
      $('.help-block').empty(); // clear error string
      $('[name="username"]').attr('readonly', false);
      $('#modal_form').modal('show'); // show modals bootstrap
      $('.modal-title').text('Thêm tài khoản');
    }
    function exit_acc(id) {
      save_method = 'update';
      $('#form')[0].reset() // reset form on modals
      $('.text-danger').css('display', 'none');
      $.ajax({
        url: '<?php echo base_url('admin/account/ajax_exit/'); ?>' + id,
        type: 'GET',
        dataType: 'json',
        success: function(data) {
          $('[name="id"]').val(data.id);
          $('[name="name"]').val(data.name);
          $('[name="username"]').val(data.username);
          $('[name="username"]').attr('readonly', true);
          $('#modal_form').modal('show');
          $('.modal-title').text('Sửa thông tin quản trị viên');
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
          url = "<?php echo base_url('admin/account/ajax_add'); ?>";
      }
      else {
          url = "<?php echo base_url('admin/account/ajax_exit'); ?>";
      }
      // Ajax adding data to database
    }



</script>
