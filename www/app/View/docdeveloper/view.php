<?php
function byteConvert($bytes) {
    if ($bytes == 0)
        return "0.00 B";

    $s = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
    $e = floor(log($bytes, 1024));

    return round($bytes/pow(1024, $e), 2).$s[$e];
}

$file_src = "/document/fileviewer/" . $this->user_id . "/" . $this->doc_id;

?>
<div class="container">
  <div class="row">
    <div class="col-md-7">
      <?php
          /* Most browsers no longer support Java Applets and Plug-ins.
             ActiveX controls are no longer supported in any browsers.
           The support for Shockwave Flash has also been turned off in modern browsers.
           You may use "iframe" or any other third party plugins
          */ 
      ?>
        <embed src="<?= $file_src; ?>" width="100%" height="500px">
    </div>
    <div class="col-md-5">
      <div class="card card-widget widget-user-2 shadow-sm">
        <!-- Add the bg color to the header using any of the bg-* classes -->
        <div class="widget-user-header bg-primary">
          <div class="widget-user-image float-left">
            <i class="far fa-file fa-4x"></i>
          </div>
          <!-- /.widget-user-image -->
          <h3 class="widget-user-username"><?= $this->file_stat->files[0]->{'file name'} ?></h3>
          <h5 class="widget-user-desc"><?= byteConvert($this->file_stat->files[0]->{'file size in bytes'}) ?></h5>
        </div>
        <div class="card-footer p-0">
          <ul id="stats" class="nav flex-column">
            <?php foreach ($this->file_stat->files[0]->rank as $key => $value): ?>
            <li class="nav-item">
              <span href="#" class="nav-link">
              <?= strtoupper($key); ?> <span class="float-right"><?= number_format(round($value, 2), 2); ?>%</span>
              </span>
            </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
      <div class="card card-info">
        <div class="card-header">
          <h3 class="card-title">File Trainer</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form class="form-horizontal" id="filetrainer">
          <div class="card-body">
            <div class="form-group row">
              <label for="type" class="col-sm-2 col-form-label">Type</label>
              <div class="col-sm-10">
                <select name="type" id="type" class="form-control">
                    <option value="" selected="" disabled="" hidden="">Choose file type</option>
                    <optgroup label="Default type" data-option="default">
                    <?php foreach ($this->file_stat->files[0]->rank as $key => $value): ?>
                      <option value="<?= strtoupper($key); ?> ">
                        <?= strtoupper($key); ?> (<?= number_format(round($value, 2), 2); ?>%)
                      </option>
                    <?php endforeach; ?>
                    </optgroup>
                    <optgroup label="All file type" data-option="all">
                      <?php foreach ($this->doc_type as $key => $value): ?>
                        <?php if (!isset($this->file_stat->files[0]->rank->{strtolower($value->type)})): ?>
                        <option value="<?= strtoupper($value->type); ?>">
                          <?= strtoupper($value->type); ?>
                        </option>
                        <?php endif; ?>
                      <?php endforeach; ?>
                    </optgroup>
                </select>
              </div>
            </div>
          </div>
          <!-- /.card-body -->
          <div class="card-footer">
            <button type="submit" class="btn btn-info float-right">Train</button>
          </div>
          <!-- /.card-footer -->
        </form>
      </div>
    </div>
  </div>
</div>
<script>
$(document).ready(function () {
    $('#filetrainer').submit(function(event) {
      event.preventDefault();
      Swal.fire({
          icon: 'info',
          title: 'Are you sure you want to train this file?',
          text: 'Document type will be change you won\'t be able to revert this!',
          showConfirmButton: true,
          showDenyButton: false,
          showCancelButton: true,
          confirmButtonText: `Start`,
          denyButtonText: `Deny`,
      }).then((result) => {
          /* I use isDenied, coz of the color */
          if (result.isConfirmed) {
            $.ajax({
                url: '/docdeveloper/learn/' + <?= $this->doc_id; ?> + '/' + <?= $this->user_id; ?>,
                type: "POST",
                dataType: "json",
                data: $(this).serializeArray(),
                beforeSend: function () {
                    $("#filetrainer").find(":submit").prop('disabled', true);
                    $("#filetrainer .card-body").append('<center id="loader"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span></center>');
                }, success: function (result) {
                    $('#loader').remove();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'File was train successfully!',
                        timer: 3000
                    });
                    console.log(result);
                    var html = "", option = "";
                    var obj = result.files[0].rank;
                    Object.keys(obj).forEach(function(key) {
                      html += '<li class="nav-item">' +
                      '<span href="#" class="nav-link">' +
                      key.toUpperCase() + '<span class="float-right">' + obj[key].toFixed(2) + '%</span>' + 
                      '</span>' +
                      '</li>';
                      option += '<option value="<?= strtoupper($key); ?> ">' +
                      key.toUpperCase() + ' (' + obj[key].toFixed(2) + '%)' +
                      '</option>';
                    });
                    $('select#type').find("optgroup[label='Default type']").html(option);
                    $('#stats').html(html);
                    $("#filetrainer ").find(":submit").prop('disabled', false);
                }
            });
          }
          if (result.isDenied) {
              resolve();
          } else if (result.isDismissed) {
              Swal.fire('Process was aborted!', 'File was not process.', 'info');
          }
      });
    })

});
</script>
