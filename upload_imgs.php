<!DOCTYPE html>
<html>
<head>
  <title></title>
  <!-- Dropzone.js -->
  <link href="<?=base_url()?>resources/css/dropzone.css" rel="stylesheet">
  <style type="text/css">
  .delete-image{ position: absolute; top: 0; right: 0; color: #fff; background: rgba(0,0,0,0.7); padding: 2px 5px; text-decoration: none; }
  .delete-image:hover{ color: #fff; }
</style>
</head>
<body>

  <div class="container">

   <div class="x_content">
    <p>Drag multiple files to the box below for multi upload or click to select files. This is for demonstration purposes only, the files are not uploaded to any server.</p>
    <form accept="image/*" multiple enctype="formdata/multipart" action="<?=base_url()?>dashboard/upload_imgs_formdata" id="myAwesomeDropzone" class="dropzone"></form>
    <br />
    <br />
    <br />
    <br />
  </div>

  <?php
  if ($uploaded_images) {
    foreach ($uploaded_images as $key => $value) { ?>
    <div class="col-md-6 col-xs-6">
      <a href="javascript:void(0)" class="delete-image" style="position: unset;"><i class="fa fa-close">X</i></a>
      <img src="<?=base_url()?>./resources/images/medium/<?=$value?>"><br>
    </div>
    <?php }
  } ?>

</div>



<!-- Dropzone.js -->
<script src="<?=base_url()?>resources/js/dropzone.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
<script type="text/javascript">

  Dropzone.options.myAwesomeDropzone  = {
            // url: 'upload_files.php',
            paramName: 'file',
            // clickable: true,
            // maxFilesize: 100,
            uploadMultiple: true, 
            maxFiles: 20,
            parallelUploads:20,
            // addRemoveLinks: true,
            // acceptedFiles: '.png,.jpg,.pdf',
            dictDefaultMessage: '',
            success: function(file, response)
            {
             console.log(response);
             // var elem = document.createElement("img");
             // elem.src = '../resources/images/add-images.png';
             // document.getElementById("myAwesomeDropzone").appendChild(elem);
             alert("Photos Upload Success! You can add more images");
             location.reload();
           }
         };

         $(document).ready(function(){
           $(".dz-message").append("<img src='../resources/images/add-images.png'><br>Click to add photos");

           $('.delete-image').on('click', function(){
            // $(this).parent().parent().find('.file-hidden').show();
            // $(this).parent().parent().find('.file-hidden').prop('required', true);
            $(this).parent().remove();
          });
           
         });

       </script>

     </body>
     </html>