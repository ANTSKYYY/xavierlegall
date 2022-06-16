<!DOCTYPE html>
<html lang="fr" class="rwd">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link href="style/main.css" rel="stylesheet">
  <link rel="shortcut icon" type="image/jpeg" href="utils/img/favicon.jpg">
  <meta meta name="viewport" content="width=device-width, user-scalable=no" />
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
  <script src="https://kit.fontawesome.com/a706e65759.js" crossorigin="anonymous"></script>
  <link rel=”stylesheet” href=”https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css”>


  <title>Xavier Legall | Accueil</title>
</head>
<body>

  <?php
    include("utils/structure/header.html")
  ?>

  <div class="image-grid" id="img-container">

    <?php
      function lister_images($repertoire){
      if(is_dir($repertoire)){
        if($iteration = opendir($repertoire)){
          while(($fichier = readdir($iteration)) !== false){
            if($fichier != "." && $fichier != ".."){
                $fichier_info = finfo_open(FILEINFO_MIME_TYPE);
                $mime_type = finfo_file($fichier_info, $repertoire.$fichier);
                if(strpos($mime_type, 'image/') === 0){

                  echo '<img id="img" src="'.$repertoire.$fichier.'" alt="architecture">';
                }
              }
          }
          closedir($iteration);
        }
      }
      }
      lister_images("utils/upload/horizontal/");



      function lister_imagess($repertoire){
        if(is_dir($repertoire)){
          if($iteration = opendir($repertoire)){
            while(($fichier = readdir($iteration)) !== false){
              if($fichier != "." && $fichier != ".."){
                  $fichier_info = finfo_open(FILEINFO_MIME_TYPE);
                  $mime_type = finfo_file($fichier_info, $repertoire.$fichier);
                  if(strpos($mime_type, 'image/') === 0){
                    echo '<img id="img" class="image-grid-col-2 image-grid-row-2" src="'.$repertoire.$fichier.'" alt="architecture">';
                  }
                }
            }
            closedir($iteration);
          }
        }
        }
        lister_imagess("utils/upload/vertical/");
    ?>
  </div>

  <?php
    include("utils/structure/footer.php")
  ?>
</body>
</html>
