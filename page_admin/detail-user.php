<?php
session_start();
$session = $_SESSION['user']['level'];
$level = ['Admin'];
if(!in_array($session, $level)) :
  echo "Halaman gagal ditampilkan.";
  exit();
endif;

$page = "Detail Data Pengguna";
include './include/heading.php';

// check whether edit_photo button has clicked
if (isset($_POST['edit_photo'])) :
  $id = $_POST["id_user"];

  // fetch image name from database
  $stmt = $pdo->prepare('SELECT username, photo FROM tbl_user WHERE id_user = :id');
  $stmt->execute([':id'=>$id]);
  $row = $stmt->fetch(PDO::FETCH_ASSOC);

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
    $imgUploadName = $row['username'].'_pic_'.time().".".$imgExt; // Sample output : user_pic_1647141687.jpg

    // execute if allowed file extension is match
    if(in_array($imgExt, $allowedExt)) :
      // execute if file size less than 1MB
      if($imgSize < 1044070) :
        unlink($uploadDir.$row['photo']);
        move_uploaded_file($tmpDir,$uploadDir.$imgUploadName);
      else :
        $errMSG = "Ukuran file lebih dari 1MB.";
        setcookie("fail", $errMSG, time()+5);
        header("Location: ".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']);
      endif;
    else :
      $errMSG = "Hanya file JPG dan PNG yang bisa diunggah.";
      setcookie("fail", $errMSG, time()+5);
      /* $_SERVER['PHP_SELF'] will goes to the wrong place
       * if htaccess url manipulation is in play the value
       */
      header("Location: ".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']);
    endif;
  else :
    $imgUploadName = $row['photo'];
  endif;

  if(!isset($errMSG)) :
    $stmt = $pdo->prepare('UPDATE tbl_user SET photo = :photo WHERE id_user = :id');
    $stmt->bindParam(':photo', $imgUploadName);
    $stmt->bindParam(':id',$id);

    if($stmt->execute()) : 
      $successMSG = "Foto pengguna berhasil diubah.";
      setcookie("success", $successMSG, time()+5);
      header("Location: ".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']);
    else :
      $errMSG = "Foto pengguna gagal diubah.";
      setcookie("fail", $errMSG, time()+5);
      header("Location: ".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']);
    endif;
  endif;
endif;

// check whether edit_user button has clicked
if (isset($_POST['edit_user'])) :
  $id = $_POST["id_user"];
  $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
  $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
  $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
  $re_password = filter_input(INPUT_POST, 're-password', FILTER_SANITIZE_STRING);
  $level = filter_input(INPUT_POST, 'user-level', FILTER_SANITIZE_STRING);

  if(!empty($re_password)) :
    if($password == $re_password) :
      // Encrypt password with password_hash()
      $password_hash = password_hash($password, PASSWORD_DEFAULT);
    else :
      $errMSG = "Konfirmasi kata sandi tidak sesuai.";
      setcookie("user_fail", $errMSG, time()+5);
      header("Location: ".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']);
    endif;
  else :
    $errMSG = "Konfirmasi kata sandi kosong.";
    setcookie("user_fail", $errMSG, time()+5);
    header("Location: ".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']);
  endif;

  if(!isset($errMSG)) :
    $stmt = $pdo->prepare('UPDATE tbl_user SET name = :name, username = :username,
      password = :password, password_hash = :password_hash, level = :level
      WHERE id_user = :id');
    $params = [
      ':name' => $name,
      ':username' => $username,
      ':password' => $password,
      ':password_hash' => $password_hash,
      ':level' => $level,
      ':id' => $id,
    ];

    if($stmt->execute($params)) :
      $successMSG = "Data pengguna berhasil diubah.";
      setcookie("user_success", $successMSG, time()+5);
      header("Location: ".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']);
    else :
      $errMSG = "Data pengguna gagal diubah.";
      setcookie("user_fail", $errMSG, time()+5);
      header("Location: ".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']);
    endif;
  endif;
endif;
?>

<div id="app">
<div class="main-wrapper">
    <?php
    include './include/navbar.php';
    include './include/sidebar.php';
    ?>

    <!-- Main Content -->
    <div class="main-content">
      <section class="section">
        <div class="section-header">
        <h1><?= $page ?></h1>
        <div class="section-header-breadcrumb">
          <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
          <div class="breadcrumb-item"><?= $page ?></div>
        </div>
        </div>
        <div class="row">
          <?php
            $id = $_GET['id-user'];
            $stmt = $pdo->prepare('SELECT * FROM tbl_user WHERE id_user=:id');
            $stmt->execute([':id'=>$id]);

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
          ?>
          <div class="col-12 col-sm-4 col-md-5">
            <div class="card">
              <div class="card-header">
                <h4>Foto Pengguna</h4>
              </div>
              <div class="card-body text-center">
                <?php
                // Mengecek apakah terdapat cookie bernama "success"
                if(isset($_COOKIE['success'])) :
                  echo" <div class='alert alert-success alert-dismissible show fade'>
                    <div class='alert-body'>
                      <button class='close' data-dismiss='alert'>
                        <span>&times;</span>
                      </button>".
                      $_COOKIE['success'].
                    "</div>
                  </div>";
                elseif(isset($_COOKIE['fail'])) :
                  echo" <div class='alert alert-danger alert-dismissible show fade'>
                    <div class='alert-body'>
                      <button class='close' data-dismiss='alert'>
                        <span>&times;</span>
                      </button>".
                      $_COOKIE['fail'].
                    "</div>
                  </div>";
                endif;
                ?>
                <figure class="avatar mr-2 avatar-xl mb-3">
                  <?php
                  if($row['photo'] != NULL) :
                    $photo = $row['photo'];
                  else:
                    $photo = "avatar-1.png";
                  endif;
                  ?>
                  <img src="../images/users/<?= $photo ?>" alt="...">
                </figure>
              </div>
              <div class="card-footer text-center bg-whitesmoke">
                <a href="#" class="btn btn-icon icon-left btn-info" data-toggle="modal" data-target="#photoModal<?= $id ?>"><i class="fas fa-image"></i> <span>Ubah Foto</span></a>
              </div>
            </div>
          </div>
          <div class="col-12 col-sm-8 col-md-7">
            <div class="card">
              <div class="card-header">
                <h4>Data Pengguna</h4>
              </div>
              <div class="card-body">
                <?php
                  // Mengecek apakah terdapat cookie bernama "user_success"
                  if(isset($_COOKIE['user_success'])) :
                    echo" <div class='alert alert-success alert-dismissible show fade'>
                      <div class='alert-body'>
                        <button class='close' data-dismiss='alert'>
                          <span>&times;</span>
                        </button>".
                        $_COOKIE['user_success'].
                      "</div>
                    </div>";
                  elseif(isset($_COOKIE['user_fail'])) :
                    echo" <div class='alert alert-danger alert-dismissible show fade'>
                      <div class='alert-body'>
                        <button class='close' data-dismiss='alert'>
                          <span>&times;</span>
                        </button>".
                        $_COOKIE['user_fail'].
                      "</div>
                    </div>";
                  endif;
                ?>
                <div><p><b>Nama :</b><br/>
                  <?= $row['name'] ?>
                </p></div>
                <div><p><b>Nama Pengguna :</b><br/>
                  <?= $row['username'] ?>
                </p></div>
                <div><p><b>Kata Sandi :</b><br/>
                  <?= $row['password'] ?>
                </p></div>
                <div><p><b>Kata Sandi Terenkripsi :</b><br/>
                  <?= $row['password_hash'] ?>
                </p></div>
                <div><p><b>Level Pengguna :</b><br/>
                  <?= $row['level'] ?>
                </p></div>

              </div>
              <div class="card-footer text-center bg-whitesmoke">
                <a href="#" class="btn btn-icon icon-left btn-success" data-toggle="modal" data-target="#userModal<?= $id ?>"><i class="fas fa-image"></i> <span>Ubah Data</span></a>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Change Photo Modal Dialog -->
      <div class="modal fade" role="dialog" id="photoModal<?= $id ?>">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Perbaharui Foto Pengguna</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="POST" action="" enctype="multipart/form-data">
              <div class="modal-body">
                <input type="hidden" id="id_user" name="id_user" value="<?= $id ?>">
                <div class="form-group text-center">
                  <figure class="avatar mr-2 avatar-xl mb-3">
                    <?php
                      $stmt = $pdo->prepare('SELECT photo FROM tbl_user WHERE id_user = :id');
                      $stmt->execute([':id'=>$id]);
                      $row = $stmt->fetch(PDO::FETCH_ASSOC);

                      $photo = !empty($row['photo']) ? $row['photo'] : "avatar-1.png";
                    ?>
                    <img src="../images/users/<?= $photo ?>" alt="..." id="preview">
                  </figure>
                </div>
                <div class="form-group">
                  <input type="file" name="photo" class="file" accept="image/*" hidden />
                  <div class="input-group my-3">
                    <input type="text" class="form-control" disabled placeholder="Unggah foto..." id="file">
                    <div class="input-group-append">
                      <button type="button" id="select_photo" class="browse btn btn-icon icon-left btn-primary"><i class="fas fa-image"></i> <span class="d-sm-none d-md-inline-block">Unggah Foto</span></button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-footer bg-whitesmoke br">
                <button type="submit" name="edit_photo" id="edit_photo" class="btn btn-icon icon-left btn-success"><i class="fas fa-save"></i> <span>Simpan Perubahan</span></button>
                <button type="button" class="btn btn-icon icon-left btn-warning" data-dismiss="modal"><i class="fas fa-times"></i> <span>Tutup</span></button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Change Data User Modal Dialog -->
      <div class="modal fade" role="dialog" id="userModal<?= $id ?>">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Ubah Data Pengguna</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="POST" action="">
              <div class="modal-body">
                <?php
                  $id = $_GET['id-user'];
                  $stmt = $pdo->prepare('SELECT * FROM tbl_user WHERE id_user=:id');
                  $stmt->execute([':id'=>$id]);

                  $row = $stmt->fetch(PDO::FETCH_ASSOC);
                ?>
                <input type="hidden" class="form-control" name="id_user" id="id_user" value="<?= $row['id_user'] ?>" required>
                <div class="form-group">
                  <label for="name">Nama</label>
                  <input type="text" class="form-control" name="name" id="name" placeholder="Nama" value="<?= $row['name'] ?>" required autocomplete="off">
                </div>
                <div class="form-group">
                  <label for="username">Nama Pengguna</label>
                  <input type="text" class="form-control" name="username" id="username" placeholder="Nama Pengguna" value="<?= $row['username'] ?>" required autocomplete="off">
                </div>
                <div class="form-group">
                  <label for="password">Kata Sandi</label>
                  <input type="password" class="form-control" name="password" id="password" placeholder="Kata Sandi" value="<?= $row['password'] ?>" required autocomplete="off">
                </div>
                <div class="form-group">
                  <label for="re-password">Konfirmasi Kata Sandi</label>
                  <input type="password" class="form-control" name="re-password" id="re-password" placeholder="Konfirmasi Kata Sandi" autocomplete="off">
                </div>
                <div class="form-group">
                  <label for="user-level">Level Pengguna</label>
                  <select name="user-level" id="user-level" class="form-control" required>
                    <option value="" disabled>Pilih Level Pengguna</option>
                    <?php
                      $query_level = "SELECT * FROM tbl_level";
                      $level = $pdo->query($query_level);

                      foreach ($level as $value) :
                        if($row['level'] == $value['user_level']) :
                          $select = "selected";
                        else :
                          $select = "";
                        endif;
                        echo "<option value='".$value['user_level']."' $select>".$value['user_level']."</option>";
                      endforeach;
                    ?>
                  </select>
                </div>

              </div>
              <div class="modal-footer bg-whitesmoke br">
                <button type="submit" name="edit_user" id="edit_user" class="btn btn-icon icon-left btn-success"><i class="fas fa-save"></i> <span>Simpan Perubahan</span></button>
                <button type="button" class="btn btn-icon icon-left btn-warning" data-dismiss="modal"><i class="fas fa-times"></i> <span>Tutup</span></button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <?php
    include './include/footer.php';
    ?>

</div>
</div>

<?php
include './include/script.php';
?>

<script type="text/javascript">
  $(document).on("click", "#select_photo", function() {
  var file = $(this).parents().find(".file");
  file.trigger("click");
  });

  $('input[type="file"]').change(function(e) {
  var fileName = e.target.files[0].name;
  $("#file").val(fileName);

  var reader = new FileReader();
  reader.onload = function(e) {
      // get loaded data and render thumbnail.
      document.getElementById("preview").src = e.target.result;
  };
  // read the image file as a data URL.
  reader.readAsDataURL(this.files[0]);
  });
</script>