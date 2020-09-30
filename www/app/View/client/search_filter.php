<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Search Filter</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form role="form">
                        <div class="card-body">
                            
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="col-form-label">
                                            SHIPMENT NUMBER
                                        </label>
                                        <input type="text" class="form-control" placeholder="Enter ...">
                                        <label class="col-form-label">
                                            CONSOL NUMBER
                                        </label>
                                        <input type="text" class="form-control" placeholder="Enter ...">
                                        <label class="col-form-label">
                                            HBL NUMBER
                                        </label>
                                        <input type="text" class="form-control" placeholder="Enter ...">
    
                                        <!-- radio -->
                                        <br/>
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input" type="radio" id="customRadio1" name="customRadio">
                                            <label for="customRadio1" class="custom-control-label">APPROVED</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input" type="radio" id="customRadio2" name="customRadio" checked>
                                            <label for="customRadio2" class="custom-control-label">DISAPPROVED</label>
                                        </div>


                                    </div>
                                    <div class="col-md-6">
                                        <label class="col-form-label">CONTAINER #</label>
                                        <select class="form-control">
                                            <option disabled selected="selected">Selected</option>
                                            <option>option 1</option>
                                            <option>option 2</option>
                                            <option>option 3</option>
                                            <option>option 4</option>
                                            <option>option 5</option>
                                        </select>

                                        <label class="col-form-label">ETA</label>
                                        <div class="input-group date" id="ETA" data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input datepicker" data-target="#ETA"/>
                                            <div class="input-group-append" data-target="#ETA" data-toggle="datepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                        <label class="col-form-label">ETD</label>
                                        <div class="input-group date" id="ETD" data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input  datepicker" data-target="#ETD"/>
                                            <div class="input-group-append" data-target="#ETD" data-toggle="datepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">SEARCH</button>
                        </div>
                    </form>
                </div>
            </div>
            <!--/.col (right) -->
        </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->