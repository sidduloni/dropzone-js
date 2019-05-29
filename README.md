# dropzone-js
Image drag and drop JS library


HTML code

<!DOCTYPE html>
<html>
<head>
  <title></title>
  <!-- Dropzone.js -->
  <link href="<?=base_url()?>resources/css/dropzone.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>

  <div class="container">

   <div class="x_content">
    <p>Drag multiple files to the box below for multi upload or click to select files. This is for demonstration purposes only, the files are not uploaded to any server.</p>
    <form multiple="multiple" action="<?=base_url()?>dashboard/upload_imgs_formdata" class="dropzone"></form>
    <br />
    <br />
    <br />
    <br />
  </div>

</div>



<!-- Dropzone.js -->
<script src="<?=base_url()?>resources/js/dropzone.js"></script>

</body>
</html>
