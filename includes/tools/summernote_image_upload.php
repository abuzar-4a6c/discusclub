<?php
require_once("security.php");
if ($logged_in) {
  // $album_name = htmlentities($_POST['album_name']);
  // $id_poster = $_SESSION['user']->id;
  if (isset($_FILES['file']) && $_FILES['file']['error'] !== 4) {
    $error = '';
      $files = $_FILES['file'];
      $target_dir = "/images/upload/";
      $target_file = $target_dir . basename($files["name"]);
      $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

      // Check if image file is a actual image or fake image
      $check = getimagesize($files["tmp_name"]);
      if ($check !== false) {
          $uploadOk = 1;
      } else {
          $error = "Sorry, geen fotobestand gevonden.";
          $uploadOk = $uploadOk = 0;
      }

      // Check if file already exists
      if (file_exists($target_file)) {
          $error = "Sorry, het bestand bestaat al.";
          $uploadOk = 0;
      }

      // Check file size
      if ($files["size"] > 500000) {
          $error = "Sorry, het bestand is te groot";
          $uploadOk = 0;
      }

      // Allow certain file formats
      if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
          && $imageFileType != "gif" && $imageFileType != "PNG") {
          $error = "Sorry, alleen JPG, JPEG, PNG & GIF bestanden zijn toegestaan.";
          $uploadOk = 0;
      }

      // Check if $uploadOk is set to 0 by an error
      if ($uploadOk == 0) {
          // header("Location: /album/upload?error=" . $error);
          exit();
          // if everything is ok, try to upload file
      } else {
          $fragments = explode('.', $files["name"]);
          $path = "/upload/" . date("Y-m-d_H-i-s") . '.' . end($fragments);

          $extensions = [
              '.png',
              '.jpg',
              '.jpeg',
              '.gif'
          ];
      }

  //          echo "<pre>";
  //	        var_dump($_FILES['file']['name'], $files["tmp_name"], sizeof($_FILES['file']['name']));
  //	        exit();
      $files = $_FILES['file'];
      if (move_uploaded_file($files["tmp_name"], '../../images' . $path)) {

        echo "/images$path";

          // $albumsql = "INSERT INTO album (title, user_id, created_at) VALUES (:title, :user_id, NOW())";
          // $album_result = $dbc->prepare($albumsql);
          // $album_result->execute([':title' => $album_name, ':user_id' => $id_poster]);
          // $album_id = $dbc->lastInsertId();

          // $sql = "INSERT INTO image (path, album_id) VALUES (:path, :album_id)";
          // $result = $dbc->prepare($sql);
          // $result->execute([':path' => $path, ':album_id' => $album_id]);

      } else {
          $error = "Sorry, er ging iets mis met het uploaden.";
          exit();
      }
    }
    // header("Location: /album/post/" . $album_id);
}
