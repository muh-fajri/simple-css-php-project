<?php

require '../connection.php';

function add_level($data) {
  global $pdo;

  $level = htmlspecialchars($data["user-level"]);

  $stmt = $pdo->prepare('INSERT INTO tbl_level (user_level) VALUES (:user_level)');
  
  $stmt->execute([':user_level' => $level]);
  return $stmt->rowCount();
}

function add_user($data) {
  global $pdo;

  $name = htmlspecialchars($data["name"]);
  $username = htmlspecialchars($data["username"]);
  $password = htmlspecialchars($data["password"]);
  $re_password = htmlspecialchars($data["re-password"]);
  $level = htmlspecialchars($data["user-level"]);
  
  // get upload image info
  $imgName = $_FILES['photo']['name'];
  /* $tmpDir should define first in file /etc/php/7.4/apache2/php.ini
   * in line upload_tmp_dir = /var/www/tmp_upload
   * and tmp_upload folder should created and change the permission
   * even the user owner
   */
  $tmpDir = $_FILES['photo']['tmp_name'];
  $imgSize = $_FILES['photo']['size'];

  // execute if input type file is not empty
  if($imgName) :
    // prepare image for uploading
    $imgExt = strtolower(pathinfo($imgName, PATHINFO_EXTENSION));
    $allowedExt = ['png', 'jpg'];
    $uploadDir = '../images/users/';
    $imgUploadName = $username.'_pic_'.time().".".$imgExt; // Sample output : user_pic_1647141687.jpg

    // execute if allowed file extension is match
    if(in_array($imgExt, $allowedExt)) :
      // execute if file size less than 1MB
      if($imgSize < 1044070) :
        move_uploaded_file($tmpDir, $uploadDir.$imgUploadName);
      else :
        $errMSG = "Ukuran file lebih dari 1MB.";
        setcookie("fail", $errMSG, time()+5);
        header("Location: ".$_SERVER['PHP_SELF']);
      endif;
    else :
      $errMSG = "Hanya file JPG dan PNG yang bisa diunggah.";
      setcookie("fail", $errMSG, time()+5);
      /* $_SERVER['PHP_SELF'] will goes to the wrong place
       * if htaccess url manipulation is in play the value
       */
      header("Location: ".$_SERVER['PHP_SELF']);
    endif;
  else :
    $imgUploadName = NULL;
  endif;

  if(!isset($errMSG)) :
    // execute if username is not empty
    if($username) :
      // fetch username from database
      $stmt = $pdo->prepare('SELECT username FROM tbl_user');
      $stmt->execute();
      $result = $stmt->fetchAll();

      $rows = [];
      foreach ($result as $row) :
        $rows[] = $row['username'];
      endforeach;

      if(!in_array($username, $rows)) :
        if(strlen($password) >= 8) :
          if($password == $re_password) :
            // Encrypt password with password_hash()
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO
              tbl_user (name, username, password, password_hash, level, photo)
              VALUES (:name, :username, :password, :password_hash, :level, :photo)');
            $params = [
              ':name' => $name,
              ':username' => $username,
              ':password' => $password,
              ':password_hash' => $password_hash,
              ':level' => $level,
              ':photo' => $imgUploadName
            ];

            if($stmt->execute($params)) : 
              $successMSG = "Data pengguna berhasil ditambahkan!";
              setcookie("success", $successMSG, time()+5);
              header("Location: ./data-user.php");
            else :
              $errMSG = "Data pengguna gagal ditambahkan!";
              setcookie("fail", $errMSG, time()+5);
              header("Location: ./data-user.php");
            endif;
          else :
            $errMSG = "Konfirmasi kata sandi tidak sama!";
            setcookie("fail", $errMSG, time()+5);
            header("Location: ./data-user.php");
          endif;
        else :
          $errMSG = "Kata sandi harus 8 karakter atau lebih.";
          setcookie("fail", $errMSG, time()+5);
          header("Location: ./data-user.php");
        endif;
      else :
        $errMSG = "Nama pengguna sudah digunakan.";
        setcookie("fail", $errMSG, time()+5);
        header("Location: ./data-user.php");
      endif;
    endif;
  endif;

  return $stmt->rowCount();
}

function add_news($data) {
  global $pdo;

  $title = htmlspecialchars($data["title"]);
  $text_content = htmlspecialchars($data["text-content"]);
  $author = htmlspecialchars($data["author"]);
  $date = htmlspecialchars($data["date-authored"]);
  
  // get upload image info
  $imgName = $_FILES['photo']['name'];
  /* $tmpDir should define first in file /etc/php/7.4/apache2/php.ini
   * in line upload_tmp_dir = /var/www/tmp_upload
   * and tmp_upload folder should created and change the permission
   * even the user owner
   */
  $tmpDir = $_FILES['photo']['tmp_name'];
  $imgSize = $_FILES['photo']['size'];

  // execute if input type file is not empty
  if($imgName) :
    // prepare image for uploading
    $imgExt = strtolower(pathinfo($imgName, PATHINFO_EXTENSION));
    $allowedExt = ['png', 'jpg'];
    $uploadDir = '../images/news/';
    $imgUploadName = 'news_'.time().".".$imgExt; // Sample output : news_1647141687.jpg

    // execute if allowed file extension is match
    if(in_array($imgExt, $allowedExt)) :
      // execute if file size less than 1MB
      if($imgSize < 1044070) :
        move_uploaded_file($tmpDir, $uploadDir.$imgUploadName);
      else :
        $errMSG = "Ukuran file lebih dari 1MB.";
        setcookie("fail", $errMSG, time()+5);
        header("Location: ".$_SERVER['PHP_SELF']);
      endif;
    else :
      $errMSG = "Hanya file JPG dan PNG yang bisa diunggah.";
      setcookie("fail", $errMSG, time()+5);
      /* $_SERVER['PHP_SELF'] will goes to the wrong place
       * if htaccess url manipulation is in play the value
       */
      header("Location: ".$_SERVER['PHP_SELF']);
    endif;
  else :
    $imgUploadName = NULL;
  endif;

  if(!isset($errMSG)) :
    // execute if title is not empty
    if($title) :
      // fetch title from database
      $stmt = $pdo->prepare('SELECT title FROM tbl_news');
      $stmt->execute();
      $result = $stmt->fetchAll();

      $rows = [];
      foreach ($result as $row) :
        $rows[] = $row['title'];
      endforeach;

      if(!in_array($title, $rows)) :
        if(!empty($date)) :
          $stmt = $pdo->prepare('INSERT INTO
            tbl_news (title, text_content, user, date, photo_news)
            VALUES (:title, :text_content, :user, :date, :photo_news)');
          $params = [
            ':title' => $title,
            ':text_content' => $text_content,
            ':user' => $author,
            ':date' => $date,
            ':photo_news' => $imgUploadName
          ];
        else :
          $stmt = $pdo->prepare('INSERT INTO
            tbl_news (title, text_content, user, photo_news)
            VALUES (:title, :text_content, :user, :photo_news)');
          $params = [
            ':title' => $title,
            ':text_content' => $text_content,
            ':user' => $author,
            ':photo_news' => $imgUploadName
          ];
        endif;

        if($stmt->execute($params)) : 
          $successMSG = "Data pengguna berhasil ditambahkan!";
          setcookie("success", $successMSG, time()+5);
          header("Location: ./data-news.php");
        else :
          $errMSG = "Data pengguna gagal ditambahkan!";
          setcookie("fail", $errMSG, time()+5);
          header("Location: ./data-news.php");
        endif;
      else :
        $errMSG = "Judul berita sudah ada.";
        setcookie("fail", $errMSG, time()+5);
        header("Location: ./data-news.php");
      endif;
    endif;
  endif;

  return $stmt->rowCount();
}

function add_product($data) {
  global $pdo;

  $cat = htmlspecialchars($data["category"]);
  $prod = htmlspecialchars($data["product"]);
  $sub_prod = htmlspecialchars($data["sub-product"]);
  $desc = htmlspecialchars($data["description"]);
  
  // Multiple files upload
  $count = count($_FILES['galleries']['name']);

  $imgUploadName = [];
  for($i=0; $i<$count; $i++) :

    // get upload image info
    $imgName = $_FILES['galleries']['name'][$i];
    /* $tmpDir should define first in file /etc/php/7.4/apache2/php.ini
    * in line upload_tmp_dir = /var/www/tmp_upload
    * and tmp_upload folder should created and change the permission
    * even the user owner
    */
    $tmpDir = $_FILES['galleries']['tmp_name'][$i];
    $imgSize = $_FILES['galleries']['size'][$i];

    // execute if input type file is not empty
    if($imgName) :
      // prepare image for uploading
      $imgExt = strtolower(pathinfo($imgName, PATHINFO_EXTENSION));
      $allowedExt = ['png', 'jpg'];
      $uploadDir = '../images/products/';
      $imgUploadName[] = 'product_'.time().".".$i.".".$imgExt; // Sample output : product_1647141687.jpg
      
      // execute if allowed file extension is match
      if(in_array($imgExt, $allowedExt)) :
        // execute if file size less than 1MB
        if($imgSize < 1044070) :
          move_uploaded_file($tmpDir, $uploadDir.$imgUploadName[$i]);
        else :
          $errMSG = "Ukuran file lebih dari 1MB.";
          setcookie("fail", $errMSG, time()+5);
          header("Location: ".$_SERVER['PHP_SELF']);
        endif;
      else :
        $errMSG = "Hanya file JPG dan PNG yang bisa diunggah.";
        setcookie("fail", $errMSG, time()+5);
        /* $_SERVER['PHP_SELF'] will goes to the wrong place
        * if htaccess url manipulation is in play the value
        */
        header("Location: ".$_SERVER['PHP_SELF']);
      endif;
    else :
      $imgUploadName = NULL;
    endif;
  endfor;

  if(!isset($errMSG)) :

    // Check if data is NULL. If not, get it them serialized
    if(!empty($imgUploadName)) :
      $serialize = serialize($imgUploadName);
    else :
      $serialize = imgUploadName;
    endif;
    print_r($serialize);

    $stmt = $pdo->prepare('INSERT INTO
      tbl_product (category, product, sub_product, description, galleries)
      VALUES (:cat, :prod, :sub_prod, :desc, :galleries)');
    $params = [
      ':cat' => $cat,
      ':prod' => $prod,
      ':sub_prod' => $sub_prod,
      ':desc' => $desc,
      ':galleries' => $serialize
    ];

    if($stmt->execute($params)) : 
      $successMSG = "Data produk berhasil ditambahkan!";
      setcookie("success", $successMSG, time()+5);
      header("Location: ./data-product.php");
    else :
      $errMSG = "Data produk gagal ditambahkan!";
      setcookie("fail", $errMSG, time()+5);
      header("Location: ./data-product.php");
    endif;
  endif;

  return $stmt->rowCount();
}

function edit_level($data) {
  global $pdo;

  $id_level = htmlspecialchars($data["id-level"]);
  $user_level = htmlspecialchars($data["user-level"]);
  
  $stmt = $pdo->prepare('UPDATE tbl_level SET
      user_level = :user_level
      WHERE id_level = :id_level');
  $params = [
      ':user_level' => $user_level,
      ':id_level' => $id_level,
  ];

  $stmt->execute($params);
  return $stmt->rowCount();
}

function edit_driver($data) {
    global $pdo;

    $id = htmlspecialchars($data["id-driver"]);
    $name = htmlspecialchars($data["name"]);
    $hp_wa = htmlspecialchars($data["hp-wa"]);

    $stmt = $pdo->prepare('UPDATE driver SET
        name = :name, no_hp_wa = :hp_wa
        WHERE id_driver = :id');

    $stmt->execute([':name' => $name, ':hp_wa' => $hp_wa, ':id' => $id]);
    return $stmt->rowCount();
}

function edit_business_trip($data) {
    global $conn;
    // global $pdo;

    /* Unable to perform query using PDO with prepared statement
     * so do mysqli. The problem is "how to set to NULL value
     * for date_today variable".
     */

    $id = htmlspecialchars($data["id-business-trip"]);
    $no_car = htmlspecialchars($data["no-car"]);
    $id_driver = htmlspecialchars($data["id-driver"]);
    $date_today = htmlspecialchars($data["date-today"]);
    $time_out = htmlspecialchars($data["time-out"]);
    $time_back = htmlspecialchars($data["time-back"]);
    $destination = htmlspecialchars($data["destination"]);

    $date_today = !empty($date_today) ? "'$date_today'" : "NULL";

    if($date_today == "NULL") :
        $time_out = "NULL";
        $time_back = "NULL";
    else :
        $time_out = !empty($time_out) ? "'$time_out'" : "NULL";
        if($time_out == "NULL") :
            $time_back = "NULL";
        else :
            $time_back = !empty($time_back) ? "'$time_back'" : "NULL";
        endif;
    endif;

    $query = "UPDATE business_trip
        SET no_car = '$no_car', id_driver = '$id_driver', date_today = $date_today, time_out = $time_out,
        time_back = $time_back, destination = '$destination'
        WHERE id_business_trip = '$id'";
    // $query = 'UPDATE business_trip
    //     SET no_car = :no_car, id_driver = :id_driver,
    //     date_today = :date_today, time_out = :time_out
    //     time_back = :time_back, destination = :destination
    //     WHERE id_business_trip = :id';
    // $params = [
    //     ':no_car' => $no_car,
    //     ':id_driver' => $id_driver,
    //     ':date_today' => $date_today,
    //     ':time_out' => $time_out,
    //     ':time_back' => $time_back,
    //     ':destination' => $destination
    // ];

    mysqli_query($conn, $query);
    // $stmt = $pdo->prepare($query);

    // $stmt->execute($params);
    return mysqli_affected_rows($conn);
    // return $stmt->rowCount();
}

function delete_level($data) {
  global $pdo;

  $id_level = htmlspecialchars($data["id-level"]);

  $stmt = $pdo->prepare('DELETE FROM tbl_level WHERE id_level = :id_level');
  $stmt->execute([':id_level' => $id_level]);
  
  return $stmt->rowCount();
}

function delete_user($data) {
    global $pdo;

    $id = htmlspecialchars($data["id-user"]);

    $stmt = $pdo->prepare('DELETE FROM tbl_user WHERE id_user = :id');
    $stmt->execute([':id' => $id]);    

    return $stmt->rowCount();
}

function delete_news($data) {
  global $pdo;

  $id = htmlspecialchars($data["id-news"]);

  $stmt = $pdo->prepare('DELETE FROM tbl_news WHERE id_news = :id');
  $stmt->execute([':id' => $id]);

  return $stmt->rowCount();
}

function delete_product($data) {
  global $pdo;

  $id = htmlspecialchars($data["id-product"]);

  $stmt = $pdo->prepare('DELETE FROM tbl_product WHERE id_product = :id');
  $stmt->execute([':id' => $id]);

  return $stmt->rowCount();
}

function delete_driver($data) {
    global $pdo;

    $id = htmlspecialchars($data["id-driver"]);

    $stmt = $pdo->prepare('DELETE FROM driver WHERE id_driver = :id');
    $stmt->execute([':id' => $id]);    

    return $stmt->rowCount();
}

function delete_business_trip($data) {
    global $pdo;

    $id_business_trip = htmlspecialchars($data["id-business-trip"]);

    $stmt = $pdo->prepare('DELETE FROM business_trip WHERE id_business_trip = :id_business_trip');
    $stmt->execute([':id_business_trip' => $id_business_trip]);

    return $stmt->rowCount();
}
?>