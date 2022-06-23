<style>
  #upload_image {
    opacity: 0;
    position: absolute;
    z-index: -1;
  }
  .set-to-profile{
    cursor: pointer;
  }
  #uploadimageModal .modal-dialog{
    width: 100%;
    height: 100%;
    margin: 36px;
    padding: 0;
}
</style>
<div class="container">
  <label class="btn btn-block btn-primary btn-sm" for="upload_image">
      <i class="fas fa-cloud-upload-alt"></i> Upload Photo
  </label>
  <input type="file" class="form-control" id="upload_image"  accept="image/*" />

  <div class="row">
    <?php foreach($this->user as $user){?>
      <div class="col-sm-12">
        <div class="card">
          <div class="card-body set-to-profile" data-imgid="<?=$user->id?>">
            <img src="<?=base64_decode($user->image_src)?>" class="img-responsive w-100">
          </div>  
        </div>   
      </div> 
      <?php } ?>               
  </div>
</div>


<div id="uploadimageModal" class="modal" role="dialog">
 <div class="modal-dialog">
  <div class="modal-content" style="width: 1400px;">
        <div class="modal-header">
          <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
          <h4 class="modal-title">Update Header and Footer Picture</h4>
        </div>
        <div class="modal-body">
          <div class="row">
       <div class="col-md-12 text-center">
        <!-- <div id="image_demo" style="width:350px; margin-top:30px"></div> -->
        <div id="image_demo"></div>
       </div>
       <div class="col-md-8">
        <button type="button" class="btn btn-primary crop_image">Set As Header And Footer Image</button>
     </div>
    </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
     </div>
    </div>
</div>
<script>
  var modalCID = $("#myModal").attr('data-cid');
  var vwidth = 450; var vheight = 450;
  var bwidth = 500; var bheight = 500;
  var imageid = $(this).attr("data-imgid");
  var imagetype = '';
  if(modalCID === 'headerupload' || modalCID === 'footerupload'){
    vwidth = 1200; vheight = 100;
    bwidth = 1300; bheight = 105;
  }
  $image_crop = $('#image_demo').croppie({
        enableExif: true,
        showZoomer: false,
        enableResize: true,
        enableOrientation: true,
        viewport: {
          width: vwidth,
          height: vheight,
          type:'square' //circle
        },
        boundary:{
          width: bwidth,
          height: bheight
        }
  });
  $('.set-to-profile').on('click',function(){
    var url = $('img',this).attr('src');
    var reader = new FileReader();
    reader.onload = function (event) {
      var image = new Image();
      image.src = event.target.result;
      console.log(event);
    }
    var image = new Image();
    $(".crop_image").attr("data-imgid",$(this).attr("data-imgid"));
    image.src = url;
    $image_crop.croppie('bind', {
      url: image.src
    }).then(function(){
      console.log('jQuery bind complete');
    });
    $('#uploadimageModal').modal('show');
  });
  $('#upload_image').on('change', function(){
    var reader = new FileReader();
    reader.onload = function (event) {
      $image_crop.croppie('bind', {
        url: event.target.result
      }).then(function(){
        console.log('jQuery bind complete');
      });
    }
    console.log(this.files);
    reader.readAsDataURL(this.files[0]);
    $('#uploadimageModal').modal('show');
  });
  $('.crop_image').click(function(event){
      // var modalCID = $("#myModal").attr('data-cid');
      switch (modalCID) {
        case 'headerupload':
          imagetype = 'Header';
          break;
        case 'footerupload':
          imagetype = 'Footer';
          break;
        case 'logoupload':
          imagetype = 'Logo';
          break;
        case 'loaderupload':
          imagetype = 'Loader';
          break;
      
        default:
          break;
      }
      if(typeof imageid ==='undefined'){
        imageid = 0;
      }
    $image_crop.croppie('result', {
      type: 'canvas',
      size: 'viewport'
    }).then(function(response){
      $.ajax({
        url: document.location.origin+"/profile/insertUserProfile/",
        type: "POST",
        data:{"image_src": response,'imageType':imagetype,'imageID':imageid},
        beforeSend: function() {
            // setting a timeout
            $('#uploaded_image').html("<p>Loading...</p>");
        },
        success:function(data)
        {
          $('#uploadimageModal').modal('hide');
          $('#uploaded_image').html(data);
          $("#profileModal").modal('hide');
          window.location.reload();
        }
      });
    })
  });
</script>