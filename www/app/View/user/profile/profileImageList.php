<style>
  #upload_image {
    opacity: 0;
    position: absolute;
    z-index: -1;
  }
</style>
<div class="container">
  <label class="btn btn-block btn-primary btn-sm" for="upload_image">
      <i class="fas fa-cloud-upload-alt"></i> Upload Photo
  </label>
  <input type="file" class="form-control" id="upload_image"  accept="image/*" />

  <div class="row">
    <div class="col-sm-12">
      <?php foreach($this->user as $user){?>
        <div class="card">
          <div class="card-body">
            <img src="<?=$user->image_src?>" class="img-responsive">
          </div>  
        </div>    
       <?php } ?>               
    </div>
  </div>
</div>


<div id="uploadimageModal" class="modal" role="dialog">
 <div class="modal-dialog">
  <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Upload & Crop Image</h4>
        </div>
        <div class="modal-body">
          <div class="row">
       <div class="col-md-8 text-center">
        <div id="image_demo" style="width:350px; margin-top:30px"></div>
       </div>
       <div class="col-md-4" style="padding-top:30px;">
        <br />
        <br />
        <br/>
        <button class="btn btn-success crop_image">Crop & Upload Image</button>
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
          width:200,
          height:200,
          type:'square' //circle
        },
        boundary:{
          width:300,
          height:300
        }
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
        
        reader.readAsDataURL(this.files[0]);
        $('#uploadimageModal').modal('show');
      });
    
      $('.crop_image').click(function(event){
        $image_crop.croppie('result', {
          type: 'canvas',
          size: 'viewport'
        }).then(function(response){
          $.ajax({
            url: document.location.origin+"/profile/insertUserProfile/",
            type: "POST",
            data:{"image_src": response},
            success:function(data)
            {
              console.log(response);
              $('#uploadimageModal').modal('hide');
              $('#uploaded_image').html(data);
              $("#profileModal").modal('hide');
            }
          });
        })
      });
</script>