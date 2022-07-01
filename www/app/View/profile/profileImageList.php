<style>
  #upload_image {
    opacity: 0;
    position: absolute;
    z-index: -1;
  }
  .set-to-profile{
    cursor: pointer;
  }
  #upload_image .modal-content{
    width: 700px;
  }
</style>
<div class="container">
  <label class="btn btn-block btn-primary btn-sm" for="upload_image">
      <i class="fas fa-cloud-upload-alt"></i> Upload Photo
  </label>
  <input type="file" class="form-control" id="upload_image" name="upload_image"  accept="image/*" />

  <div class="row">
    <?php foreach($this->user as $user){?>
      <?php if($user->image_type =="profile"){?>
      <div class="col-sm-4">
        <div class="card">
          <div class="card-body set-to-profile" data-imgid="<?=$user->id?>">
            <img src="<?=base64_decode($user->image_src)?>" class="img-responsive">
          </div>  
        </div>   
      </div> 
      <?php } } ?>               
  </div>
</div>


<div id="uploadimageModal" class="modal" role="dialog">
 <div class="modal-dialog">
  <div class="modal-content" style="width:700px;">
        <div class="modal-header">
          <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
          <h4 class="modal-title">Update Profile Picture</h4>
        </div>
        <div class="modal-body">
          <div class="row">
       <div class="col-md-12 text-center">
        <!-- <div id="image_demo" style="width:350px; margin-top:30px"></div> -->
        <div id="image_demo"></div>
       </div>
       <div class="col-md-8">
        <button type="button" class="btn btn-primary crop_image" >Set Profile Picture</button>
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
  $image_crop = $('#image_demo').croppie({
        enableExif: true,
        viewport: {
          width:350,
          height:350,
          type:'square' //circle
        },
        boundary:{
          width:450,
          height:450
        }
  });

  $('.set-to-profile').on('click',function(){
    var url = $('img',this).attr('src');
    $(".crop_image").attr("data-imgid",$(this).attr("data-imgid"));
    // var reader = new FileReader();
    // reader.onload = function (event) {
    //   var image = new Image();
    //   image.src = event.target.result;
    //   console.log(event);
    // }
    var image = new Image();
    image.src = url;
    $image_crop.croppie('bind', {
      url: image.src
    }).then(function(){
      console.log('jQuery bind complete');
    });
    $('#uploadimageModal').modal('show');
  });

  var fileholder =''; 
  var reader = new FileReader();
  var fdata = new FormData();

  $('#upload_image').on('change', function(){
    var reader = new FileReader();
    reader.onload = function (event) {
      $image_crop.croppie('bind', {
        url: event.target.result
      }).then(function(){
        console.log('jQuery bind complete');
      });
    }
    fileholder = this.files;
    var file_data = $('#upload_image').prop('files');  
    fdata.append('file',file_data[0]);

    reader.readAsDataURL(this.files[0]);
    $('#uploadimageModal').modal('show');
  });
    
  $('.crop_image').click(function(event){
    var imageid = $(this).attr("data-imgid");
    if(typeof imageid ==='undefined'){
        imageid = 0;
      }
    $image_crop.croppie('result', {
      type: 'canvas',
      size: 'viewport'
    }).then(function(response){
      fdata.append('imageType','profile');
      fdata.append('imageID',imageid);
      
      $.ajax({
        url: document.location.origin+"/profile/insertUserProfile/",
        type: "POST",
        data:fdata,
        contentType: false,
        cache:false,
        processData:false,
        //data:{"image_src": response,'imageType':'profile','imageID':imageid},
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