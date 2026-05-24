<?php
$error = '';

//pokud je formular poslan
if(isset($_POST["upload"])){
    //ziskam si informace o obrazku
    $fileName   = basename($_FILES["image"]["name"]);
    $fileTmp    = $_FILES["image"]["tmp_name"];
    $fileType   = $_FILES["image"]["type"];
    $fileSize   = $_FILES["image"]["size"];
    $fileExt    = substr($fileName, strrpos($fileName, ".") + 1);
    $default = "obr.jpg";
    //definovani cesty, kde si budu ukladat orez
    $largeImageLoc = 'uploads/images/'.$default;
    $thumbImageLoc = 'uploads/images/thumb/'.$default;

    // kontrola, asi ma soubor validni priponu
    if((!empty($_FILES["image"])) && ($_FILES["image"]["error"] == 0)){
        if($fileExt != "jpg" && $fileExt != "jpeg" && $fileExt != "png" && $fileExt != "gif"){
            $error = "Spatny format";
        }
    }else{
        $error = "Vyberte obrazek pro orez";
    }

    //pokud je vse ok, stahneme obrazek
    if(empty($error) && !empty($fileName)){
        if(move_uploaded_file($fileTmp, $largeImageLoc)){
            // nastavime prava
            chmod($largeImageLoc, 0777);

            //ziskani rozmeru originalniho obrazku
            list($width_org, $height_org) = getimagesize($largeImageLoc);

            //ziskani koordinat obrazku
            $x = (int) $_POST['x'];
            $y = (int) $_POST['y'];
            $width = (int) $_POST['w'];
            $height = (int) $_POST['h'];

            //definice promennych pro novou velikost
            $width_new = $width;
            $height_new = $height;

            //vytvoreni noveho obrazku
            $newImage = imagecreatetruecolor($width_new, $height_new);

            //vytovreni noveho obrazku ze souboru
            switch($fileType) {
                case "image/gif":
                    $source = imagecreatefromgif($largeImageLoc);
                    break;
                case "image/pjpeg":
                case "image/jpeg":
                case "image/jpg":
                    $source = imagecreatefromjpeg($largeImageLoc);
                    break;
                case "image/png":
                case "image/x-png":
                    $source = imagecreatefrompng($largeImageLoc);
                    break;
            }

            //vytvoreni kopie a resize
            imagecopyresampled($newImage, $source, 0, 0, $x, $y, $width_new, $height_new, $width, $height);

            //vytvoreni noveho souboru uz s oriznutym souborem
            switch($fileType) {
                case "image/gif":
                    imagegif($newImage, $thumbImageLoc);
                    break;
                case "image/pjpeg":
                case "image/jpeg":
                case "image/jpg":
                    imagejpeg($newImage, $thumbImageLoc, 90);
                    break;
                case "image/png":
                case "image/x-png":
                    imagepng($newImage, $thumbImageLoc);
                    break;
            }

            //smazani obrazku
            imagedestroy($newImage);


          echo '<!DOCTYPE html>
          <html lang="cs">
          <!-- cesky jazyk -->

          <head>
              <title>Foto editor</title>
              <!-- nazev stranky -->
              <meta charset="utf-8">

              <!-- jQuery knihovna -->
              <script src="js/jquery.min.js"></script>

              <!-- externi odkaz na style.css -->
              <link rel="stylesheet" type="text/css" href="style.css">
              <!-- imgAreaSelect plugin -->
              <link rel="stylesheet" href="css/imgareaselect.css" />
              <script src="js/jquery.imgareaselect.js"></script>

              <script>
                  // funkce pro zkontrolovani, asi uzivatel oznacil nejakou oblast
                  function checkCoords() {
                      if (parseInt($("#w").val())) return true;
                      alert("Vyberte nejakou oblast.");
                      return false;
                  }

                  // nastaveni pixelu, ktere uzivatel oznacil
                  function updateCoords(im, obj) {
                      var img = document.getElementById("imagePreview"); //ziskani obrazku
                      var orgHeight = img.naturalHeight; //nahrany obrazek stejne vysoky jako puvodni
                      var orgWidth = img.naturalWidth; //nahrany obrazek stejne siroky jako puvodni

                      var porcX = orgWidth / im.width; //vypocty pro urceni zacatku x
                      var porcY = orgHeight / im.height; //vypocty pro urceni zacatku y

                      $("input#x").val(Math.round(obj.x1 * porcX)); //vypocty pro ziskani plochy
                      $("input#y").val(Math.round(obj.y1 * porcY));
                      $("input#w").val(Math.round(obj.width * porcX));
                      $("input#h").val(Math.round(obj.height * porcY));
                  }

                  $(document).ready(function() {
                      // pripraveni obrazku
                      var p = $("#imagePreview");
                      $("#fileInput").change(function() {
                          //schovani puvodniho obrazku
                          p.fadeOut();

                          //priprava filereaderu pro vypis nahraneho obrazku
                          var oFReader = new FileReader();
                          oFReader.readAsDataURL(document.getElementById("fileInput").files[0]);

                          oFReader.onload = function(oFREvent) {
                              p.attr("src", oFREvent.target.result).fadeIn();
                          };
                      });

                      //imageareaselect plugin
                      $("#imagePreview").imgAreaSelect({
                          onSelectEnd: updateCoords
                      });
                  });
              </script>

              <!-- externi odkaz na style.css  -->
              <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
          </head>

          <body>
              <div class="container">
                  <!-- globalni div -->
                  <ul>
                      <!-- zacatek seznamu -->
                      <li style="float:right"><a href="editor.html">Efekty</a></li>
                      <li style="float:right"><a href="index.html">Orezy</a></li>
                      <li style="float:right"><a href="resize.html">Resize</a></li>

                  </ul>
                  <!-- konec seznamu -->
                  <div id="upload">
                      <!-- sekce programu pro nahrani obrazku -->
                      <p class="nadpis">Foto Editor - Sekce <u>OREZ</u></p>
                      <form method="post" action="upload.php" enctype="multipart/form-data" onsubmit="return checkCoords();">
                          <!-- vybrani obrazku a poslani na php -->
                          <p class="podnadpis">Kliknutim na tlacitko "Vybrat soubor" muzete vybrat obrazek k orezu.
                              <br>
                              <input name="image" id="fileInput" size="30" type="file" />
                              <br>
                          </p>
                          <input type="hidden" id="x" name="x" />
                          <!-- schovane souradnice obrazku -->
                          <input type="hidden" id="y" name="y" />
                          <input type="hidden" id="w" name="w" />
                          <input type="hidden" id="h" name="h" />
                          <div class="tlacitko">
                              <input name="upload" type="submit" height="100px" value="Orez" />
                              <!-- konec sekce pro nahrani obrazku -->
                          </div>
                      </form>

                  </div>
                  <center>
                      <!-- sekce pro zobrazeni obrazku -->
                      <p><img id="imagePreview" style="display:none;" /></p>
                  </center>
              </div>
              </div>
          </body>

          </html>
';

                  echo   '<script src="script.js"></script>';

          echo   '<center><br/><img src="'.$thumbImageLoc.'" download/></center>';

        echo'      <center>
              <div id="save">';
        echo '     <a href="uploads/images/thumb/obr.jpg" download>   <button class="uloz" onclick="save2()" download>Save</button></a>';
        echo '           <a href="index.html"> <button>Novy obrazek</button></a>';
        echo '      </div>
              <center>



            </body>
            </html>
';



        }else{
            $error = "Soubor se nepodarilo otevrit.";
        }
    }
}


echo $error;
?>
