<?php
  require_once('filebrowser.php');

  $path = isset($_GET['root']) ? $_GET['root'] : null;
  $subPath = isset($_GET['sub']) ? $_GET['sub'] : null;
  $type = isset($_GET['type']) ? $_GET['type'] : null;
  $filter = !is_null($type) ? explode(",", $type) : [];
  $browser = new FileBrowser($path, $subPath, $filter);
?>

<!DOCTYPE html>
<html lang="en">
 <head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>File browser</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.7.4/css/bulma.min.css">
 </head>
 <body>
   <div class="container">
   <div class="notification">
      <?php
        $files = $browser->Get();
        foreach($files as $file) {
           echo $file;
        }
      ?>
   </div>
 </div>
 </body>
</html>
