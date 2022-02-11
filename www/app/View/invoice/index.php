<?php
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
?>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-sm-12 col-md-6 col-lg-6 col-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">File Upload</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="file-loading">
                            <input id="invoice" name="invoice[]" type="file" class="file" accept="application/*" multiple 
                            data-preview-file-type="text" 
                            data-allowed-file-extensions='["doc", "docx"]'>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer clearfix">
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-6 col-6">
                <div class="card">
                <div class="card-header">
                    <h3 class="card-title">File List</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table class="table">
                    <thead>
                        <tr>
                        <th style="width: 10px">#</th>
                        <th>File Name</th>
                        <th>Date Uploaded</th>
                        <th>Uploaded By</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                        <td>1.</td>
                        <td>Invoice.pdf</td>
                        <td> November 12, 2021
                            <div class="progress progress-xs d-none">
                            <div class="progress-bar progress-bar-danger" style="width: 55%"></div>
                            </div>
                        </td>
                        <td> Alex
                            <span class="badge bg-danger d-none">55%</span></td>
                        </tr>
                        <tr>
                        <td>2.</td>
                        <td>Invoice2017.pdf</td>
                        <td> February 3, 2021
                            <div class="progress progress-xs d-none">
                            <div class="progress-bar bg-warning d-none" style="width: 70%"></div>
                            </div>
                        </td>
                        <td> Ronnel
                            <span class="badge bg-warning d-none">70%</span></td>
                        </tr>
                        <tr>
                        <td>3.</td>
                        <td>Invoice2018.pdf</td>
                        <td> January 14, 2021
                            <div class="progress progress-xs progress-striped active d-none">
                            <div class="progress-bar bg-primary" style="width: 30%"></div>
                            </div>
                        </td>
                        <td> Neil
                            <span class="badge bg-primary d-none">30%</span></td>
                        </tr>
                        <tr>
                        <td>4.</td>
                        <td>Invoice2022.pdf</td>
                        <td> January 29, 2021
                            <div class="progress progress-xs progress-striped active d-none">
                            <div class="progress-bar bg-success" style="width: 90%"></div>
                            </div>
                        </td>
                        <td> Ninjie
                            <span class="badge bg-success d-none">90%</span></td>
                        </tr>
                    </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
                <div class="card-footer clearfix">
                    <ul class="pagination pagination-sm m-0 float-right">
                    <li class="page-item"><a class="page-link" href="#">«</a></li>
                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">»</a></li>
                    </ul>
                </div>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
<script>
var user_id = <?= $this->user_id; ?>;
var token = "<?= generateRandomString(); ?>";
</script>