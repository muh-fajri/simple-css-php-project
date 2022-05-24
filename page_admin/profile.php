<?php
$page = "Profil";
include './include/heading.php';

// check whether del_photo button has clicked
if(isset($_POST["del_photo"])) :
  $id = $_POST["id_user"];

  // fetch image name from database
  $stmt = $pdo->prepare('SELECT photo FROM user WHERE id_user = :id');
  $stmt->execute([':id'=>$id]);
  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  if(!empty($row['photo'])) :

    unlink("../assets/img/avatar/".$row['photo']);
    $stmt_del = $pdo->prepare('UPDATE user SET photo = NULL WHERE id_user = :id');

    if($stmt_del->execute([':id'=>$id])) :
      setcookie("success", "Foto profil berhasil dihapus. <em>Login</em> kembali untuk menerapkan perubahan.", time()+5);
      header("Location: ./profile.php");
    else :
      setcookie("fail", "Foto profil gagal dihapus.", time()+5);
      header("Location: ./profile.php");
    endif;
  else :
    setcookie("fail", "Foto profil kosong.", time()+5);
    header("Location: ./profile.php");    
  endif;
endif;

// check whether save_pass button has clicked
if(isset($_POST["save_pass"])) :
  $id = $_POST["id_user"];
  $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
  $re_password = filter_input(INPUT_POST, 're-password', FILTER_SANITIZE_STRING);

  if(!empty($password)) :
    if(!empty($re_password)) :
      if($password === $re_password) :
        // Encrypt password with password_hash()
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
      else :
        $errMSG = "Konfirmasi kata sandi tidak sama.";
        setcookie("fail", $errMSG, time()+5);
        header("Location: ".$_SERVER['PHP_SELF']);
      endif;
    else :
      $errMSG = "Konfirmasi kata sandi harus diisi.";
      setcookie("fail", $errMSG, time()+5);
      header("Location: ".$_SERVER['PHP_SELF']);
    endif;
  else :
    $errMSG = "Kata sandi baru tidak boleh kosong.";
    setcookie("fail", $errMSG, time()+5);
    header("Location: ".$_SERVER['PHP_SELF']);
  endif;

  if(!isset($errMSG)) :
    $stmt = $pdo->prepare('UPDATE user SET password = :password, password_hash = :password_hash WHERE id_user = :id');
    $stmt->bindParam(':password',$password);
    $stmt->bindParam(':password_hash',$password_hash);
    $stmt->bindParam(':id',$id);

    if($stmt->execute()) :
      setcookie("success", "Kata sandi berhasil diubah. <em>Login</em> kembali untuk menerapkan perubahan.", time()+5);
      header("Location: ".$_SERVER['PHP_SELF']);
    else :
      setcookie("fail", "Kata sandi gagal diubah.", time()+5);
      header("Location: ".$_SERVER['PHP_SELF']);
    endif;
  endif;
endif;

// check whether edit_profile button has clicked
if (isset($_POST['edit_profile'])) :
  $id = $_POST["id_user"];
  $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
  $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);

  // fetch image name from database
  $stmt = $pdo->prepare('SELECT photo FROM tbl_user WHERE id_user = :id');
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

  if($imgName) :

    // prepare image for uploading
    $imgExt = strtolower(pathinfo($imgName, PATHINFO_EXTENSION));
    $allowedExt = ['png', 'jpg'];
    $uploadDir = '../images/users/';
    $imgUploadName = $username.'_pic_'.time().".".$imgExt;

    if(in_array($imgExt, $allowedExt)) :
      if($imgSize < 1044070) :
        unlink($uploadDir.$row['photo']);
        move_uploaded_file($tmpDir, $uploadDir.$imgUploadName);
      else :
        $errMSG = "Ukuran file lebih dari 1MB.";
        setcookie("fail", $errMSG, time()+5);
        header("Location: ".$_SERVER['PHP_SELF']);
      endif;
    else :
      $errMSG = "Hanya file JPG dan PNG yang bisa diunggah.";
      setcookie("fail", $errMSG, time()+5);
      header("Location: ".$_SERVER['PHP_SELF']);
    endif;
  else :
    $imgUploadName = $row['photo'];
  endif;

  if(!isset($errMSG)) :
    $stmt = $pdo->prepare('UPDATE tbl_user SET name = :name, username = :username, photo = :photo WHERE id_user = :id');
    $stmt->bindParam(':name',$name);
    $stmt->bindParam(':username',$username);
    $stmt->bindParam(':photo',$imgUploadName);
    $stmt->bindParam(':id',$id);

    if($stmt->execute()) :
      setcookie("success", "Data profil berhasil diubah. <em>Login</em> kembali untuk menerapkan perubahan.", time()+5);
      header("Location: ./profile.php");
    else :
      setcookie("fail", "Data profil gagal diubah.", time()+5);
      header("Location: ./profile.php");
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
            <div class="breadcrumb-item active"><a href="#">Halaman Awal</a></div>
            <div class="breadcrumb-item"><?= $page ?></div>
          </div>
        </div>

        <div class="section-body">
          <h2 class="section-title">Profil</h2>
          <p class="section-lead">Menampilkan profil pengguna yang sedang <i>login</i>.</p>
          <div class="card">
            <div class="card-header">
              <h4>Profil Pengguna</h4>
            </div>
            <div class="card-body text-center">
              <?php
                // Mengecek apakah terdapat session bernama "success"
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
              <div class="form-group text-center">
                <figure class="avatar mr-2 avatar-xl mb-3">
                  <?php
                  if($_SESSION['user']['photo']) :
                    $photo = $_SESSION['user']['photo'];
                  else:
                    $photo = "avatar-1.png";
                  endif;
                  ?>
                  <img src="../images/users/<?= $photo ?>" alt="...">
                  <i class="avatar-presence online"></i>
                </figure><br />
                <form method="POST" action="" enctype="multipart/form-data">
                  <input type="hidden" id="id_user" name="id_user" value="<?= $_SESSION["user"]["id_user"] ?>">
                  <button type="submit" class="btn btn-sm btn-icon icon-left btn-danger" name="del_photo" onclick="return confirm('Ingin menghapus foto profil?');"><i class="fas fa-image"></i> <span>Hapus Foto</span></button>
                </form>
              </div>
              <div>
                <h5><?= $_SESSION["user"]["name"] ?></h5>
                <b><i>(Level Pengguna : <?= $_SESSION["user"]["level"] ?>)</i></b>
              </div>
              <br />
              <div><b>Nama Pengguna / Kata Sandi :</b><br />
                <?= $_SESSION["user"]["username"] ?> / <?= $_SESSION["user"]["password"] ?></div>
            </div>
            <div class="card-footer text-center bg-whitesmoke">
              <a href="#" class="btn btn-icon icon-left btn-info" data-toggle="modal" data-target="#passModal<?= $_SESSION['user']['id_user']; ?>"><i class="fas fa-key"></i> <span>Ubah Kata Sandi</span></a>
              <a href="#" class="btn btn-icon icon-left btn-success" data-toggle="modal" data-target="#profileModal<?= $_SESSION['user']['id_user']; ?>"><i class="fas fa-user-alt"></i> <span>Ubah Profil</span></a>
            </div>
          </div>
        </div>
      </section>

      <!-- Change Password Modal Dialog -->
      <div class="modal fade" role="dialog" id="passModal<?= $_SESSION['user']['id_user'] ?>">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Perbaharui Kata Sandi</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form role="form" method="POST" action="">
              <div class="modal-body">
                <p>Isi form berikut ini untuk mengubah data Anda.</p>
                <input type="hidden" id="id_user" name="id_user" value="<?= $_SESSION["user"]["id_user"] ?>">
                <div class="form-group">
                  <label for="old-password">Kata Sandi</label>
                  <input type="password" id="old-password" name="old-password" class="form-control" value="<?= $_SESSION["user"]["password"] ?>" required="">
                </div>
                <div class="form-group">
                  <label for="password">Kata Sandi Baru</label>
                  <input type="password" id="password" name="password" class="form-control">
                </div>
                <div class="form-group">
                  <label for="re-password">Konfirmasi Kata Sandi</label>
                  <input type="password" id="re-password" name="re-password" class="form-control">
                </div>
              </div>
              <div class="modal-footer bg-whitesmoke br">
                <button type="submit" name="save_pass" id="save_pass" class="btn btn-icon icon-left btn-success"><i class="fas fa-save"></i> <span>Simpan Perubahan</span></button>
                <button type="button" class="btn btn-icon icon-left btn-warning" data-dismiss="modal"><i class="fas fa-times"></i> <span>Tutup</span></button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Change Profile Modal Dialog -->
      <div class="modal fade" role="dialog" id="profileModal<?= $_SESSION['user']['id_user'] ?>">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Perbaharui Profil</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="POST" action="" enctype="multipart/form-data">
              <div class="modal-body">
                <p>Isi form berikut ini untuk mengubah data Anda.</p>
                <input type="hidden" id="id_user" name="id_user" value="<?= $_SESSION["user"]["id_user"] ?>">
                <div class="form-group text-center">
                  <figure class="avatar mr-2 avatar-xl mb-3">
                    <?php
                    if($_SESSION['user']['photo'] != NULL) :
                      $photo = $_SESSION['user']['photo'];
                    else:
                      $photo = "avatar-1.png";
                    endif;
                    ?>
                    <img src="../images/users/<?= $photo ?>" alt="..." id="preview">
                    <i class="avatar-presence online"></i>
                  </figure>
                </div>
                <div class="form-group">
                  <input type="file" name="photo" class="file" hidden>
                  <div class="input-group my-3">
                    <input type="text" class="form-control" disabled placeholder="Unggah foto..." id="file">
                    <div class="input-group-append">
                      <button type="button" id="select_photo" class="browse btn btn-icon icon-left btn-primary"><i class="fas fa-image"></i> <span class="d-sm-none d-md-inline-block">Unggah Foto</span></button>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="name">Nama</label>
                  <input type="text" id="name" name="name" class="form-control" value="<?= $_SESSION["user"]["name"] ?>" required autocomplete="off">
                </div>
                <div class="form-group">
                  <label for="username">Nama Pengguna</label>
                  <input type="text" id="username" name="username" class="form-control" value="<?= $_SESSION["user"]["username"] ?>" required autocomplete="off">
                </div>
              </div>
              <div class="modal-footer bg-whitesmoke br">
                <button type="submit" name="edit_profile" id="edit_profile" class="btn btn-icon icon-left btn-success"><i class="fas fa-save"></i> <span>Simpan Perubahan</span></button>
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