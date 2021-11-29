<?php $this->load->view('backend/header'); ?>
<?php $this->load->view('backend/sidebar'); ?>
    <div class="page-wrapper">
        <div class="message"></div>
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h3 class="text-themecolor"><i class="fa fa-university" aria-hidden="true"></i> Queries</h3>
            </div>
            <div class="col-md-7 align-self-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                    <li class="breadcrumb-item active">Queries</li>
                </ol>
            </div>
        </div>
        <!-- Container fluid  -->
        <!-- ============================================================== -->
        <div class="container-fluid">
        <?php $status = null; ?>
            <div class="row m-b-10"> 
                <div class="col-12">
                    <button type="button" class="btn btn-info"><i class="fa fa-plus"></i><a data-toggle="modal" data-target="#exampleModal" data-whatever="@getbootstrap"  class="text-white"><i class="" aria-hidden="true"></i> Add Disciplinary </a></button>
                    <button type="button" class="btn btn-primary"><i class="fa fa-bars"></i><a href="<?php echo base_url(); ?>employee/Employees" class="text-white"><i class="" aria-hidden="true"></i>  Employee List</a></button>
                </div>
            </div>         
            <div class="row">
                <div class="col-12">
                    <div class="card card-outline-info">
                        <div class="card-header">
                            <h4 class="m-b-0 text-white"> Disciplinary Action List</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive ">
                                <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Title </th>
                                            <th>Description</th>
                                            <th>Disciplinary Action</th>
                                            <th>Status</th>
                                            <!-- <th></th> -->
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php foreach($queries as $value): ?>
                                        <tr>
                                            <td ><?php echo substr("$value->title",0,15).'...' ?></td>
                                            <td><?php echo substr("$value->description",0,10).'...' ?> </td>
                                            <td><?php echo $value->action; ?></td>
                                            <td><?php $value->status == null ?  $status = 'In Progress':  $status = $value->status; echo $status;?></td>
                                            <td  class="jsgrid-align-center ">
                                                <a href="#" title="Edit" class="btn btn-sm btn-info waves-effect waves-light disiplinary" data-id="<?php echo $value->id; ?>"><i class="fa fa-pencil-square-o"></i></a>
                                                <a href="DeletDisiplinary?D=<?php echo $value->id; ?>" onclick="confirm('Are you sure want to delete this value?')" title="Delete" class="btn btn-sm btn-info waves-effect waves-light"><i class="fa fa-trash-o"></i></a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <!-- sample modal content -->
                                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content ">
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="exampleModalLabel1">Disciplinary Notice</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            </div>
                                            <form method="post" action="add_Desciplinary" id="btnSubmit" enctype="multipart/form-data">
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <input type="hidden" name="id">
                                                    <input type="hidden" name="emid">
                                                    <label for="recipient-name" class="control-label">Verbal Warning</label>
                                                    <input type="text" readonly name="warning" class="form-control" >
                                                </div>
                                                <div class="form-group">
                                                    <label for="recipient-name" class="control-label">Title</label>
                                                    <input type="text" readonly name="title" class="form-control" id="title">
                                                </div>
                                                <div class="form-group">
                                                    <label for="message-text" class="control-label">Details</label>
                                                    <textarea readonly class="form-control" name="details" id="message-text1" rows="4"></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="message-text" class="control-label">Response</label>
                                                    <textarea  class="form-control" name="response" id="response" rows="4"></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <input type="hidden" name="id" value="">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.modal -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            $(document).ready(function () {
                $(".disiplinary").click(function (e) {
                    e.preventDefault(e);
                    // Get the record's ID via attribute  
                    var iid = $(this).attr('data-id');
                    $('#btnSubmit').trigger("reset");
                    $('#exampleModal').modal('show');
                    $.ajax({
                        url: 'DisiplinaryByID?id=' + iid,
                        method: 'GET',
                        data: '',
                        dataType: 'json',
                    }).done(function (response) {
                        console.log(response);
                        // Populate the form fields with the data returned from server
                        $('#btnSubmit').find('[name="id"]').val(response.desipplinary.id).end();
                        $('#btnSubmit').find('[name="emid"]').val(response.desipplinary.em_id).end();
                        $('#btnSubmit').find('[name="warning"]').val(response.desipplinary.action).end();
                        $('#btnSubmit').find('[name="title"]').val(response.desipplinary.title).end();
                        $('#btnSubmit').find('[name="details"]').val(response.desipplinary.description).end();
                    });
                });
            });
        </script> 
<?php $this->load->view('backend/footer'); ?>
