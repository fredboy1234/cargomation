  <!-- Content Wrapper. Contains page content -->
  <style>
    .ubanner{
      width: 100%;
      height: 95px;
    }
  </style>
  
  <?php 
  
   if(isset($this->user->user_settings[0])){
     $styleof = json_decode($this->user->user_settings[0]->colorScheme);
     if(!empty($styleof->colorObject)){
      echo"<style>{$styleof->colorObject}</style>";
     }
     
   }
  ?>
  
  <?php $imageMisc=""; if(isset($this->user->user_banner)){
    foreach($this->user->user_banner as $src_image){
      if($src_image->image_type === "Header"){
        if (preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $src_image->image_src)){
          $imageMisc = base64_decode($src_image->image_src);
        }else{
          $imageMisc = $src_image->image_src;
        }
      
      }
    }
  }?>
  <div class="content-wrapper">
    <?php if($imageMisc !== ''){?>
    <nav class="navbar">
    <div class="container-fluid">
      <a class="navbar-brand text-center w-100 bg-white" href="#">
        <!-- <div style='width:100%; height:95px; background-image: url("https://cargomation.com/filemanager/a2b@a2bsolutiongroup.com/USER_IMAGES/banner/silav1.png");background-position: center;background-repeat: no-repeat;background-size: cover;'></div> -->
        <img src="<?=$imageMisc?>" alt="" style="width:100%; height:95;" class="d-inline-block align-text-top  ubanner"><br>
      </a>
      
    </div>
    </nav>
    <?php } ?>
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 h1-cus"><?= $this->escapeHTML(strtoupper($this->title)); ?></h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/">Home</a></li>
              <li class="breadcrumb-item active"><?= $this->escapeHTML($this->title); ?></li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->