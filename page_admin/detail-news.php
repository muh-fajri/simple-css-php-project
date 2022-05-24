<?php
session_start();
$session = $_SESSION['user']['level'];
$level = ['Admin'];
if(!in_array($session, $level)) :
  echo "Halaman gagal ditampilkan.";
  exit();
endif;

$page = "Detail Data Berita";
include './include/heading.php';

// check whether edit_photo button has clicked
if (isset($_POST['edit_photo'])) :
  $id = $_POST["id_news"];

  // fetch image name from database
  $stmt = $pdo->prepare('SELECT photo_news FROM tbl_news WHERE id_news = :id');
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
    $uploadDir = '../images/news/';
    $imgUploadName = 'news_'.time().".".$imgExt; // Sample output : news_1647141687.jpg

    // execute if allowed file extension is match
    if(in_array($imgExt, $allowedExt)) :
      // execute if file size less than 1MB
      if($imgSize < 1044070) :
        unlink($uploadDir.$row['photo_news']);
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
    $imgUploadName = $row['photo_news'];
  endif;

  if(!isset($errMSG)) :
    $stmt = $pdo->prepare('UPDATE tbl_news SET photo_news = :photo WHERE id_news = :id');
    $stmt->bindParam(':photo', $imgUploadName);
    $stmt->bindParam(':id',$id);

    if($stmt->execute()) : 
      $successMSG = "Foto berita berhasil diubah.";
      setcookie("success", $successMSG, time()+5);
      header("Location: ".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']);
    else :
      $errMSG = "Foto berita gagal diubah.";
      setcookie("fail", $errMSG, time()+5);
      header("Location: ".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']);
    endif;
  endif;
endif;

// check whether edit_news button has clicked
if (isset($_POST['edit_news'])) :
  $id = $_POST["id_news"];
  $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
  $text_content = filter_input(INPUT_POST, 'text-content', FILTER_SANITIZE_STRING);
  $date = filter_input(INPUT_POST, 'date-authored', FILTER_SANITIZE_STRING);
  $author = filter_input(INPUT_POST, 'author', FILTER_SANITIZE_STRING);

  if(!empty($date)) :
    $stmt = $pdo->prepare('UPDATE tbl_news SET title = :title, text_content = :text_content,
      date = :date, user = :user WHERE id_news = :id');
    $params = [
      ':title' => $title,
      ':text_content' => $text_content,
      ':date' => $date,
      ':user' => $author,
      ':id' => $id
    ];
  else :
    $stmt = $pdo->prepare('UPDATE tbl_news SET title = :title, text_content = :text_content,
      user = :user WHERE id_news = :id');
    $params = [
      ':title' => $title,
      ':text_content' => $text_content,
      ':user' => $author,
      ':id' => $id
    ];
  endif;

  if($stmt->execute($params)) :
    $successMSG = "Data berita berhasil diubah.";
    setcookie("user_success", $successMSG, time()+5);
    header("Location: ".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']);
  else :
    $errMSG = "Data berita gagal diubah.";
    setcookie("user_fail", $errMSG, time()+5);
    header("Location: ".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']);
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
            $id = $_GET['id-news'];
            $stmt = $pdo->prepare('SELECT * FROM tbl_news a JOIN tbl_user b ON
              a.user=b.id_user WHERE id_news=:id');
            $stmt->execute([':id'=>$id]);

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
          ?>
          <div class="col-12 col-sm-4 col-md-5">
            <div class="card">
              <div class="card-header">
                <h4>Foto Berita</h4>
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
                <?php
                if($row['photo_news'] != NULL) :
                  $photo = "news/".$row['photo_news'];
                else:
                  $photo = "p-150.png";
                endif;
                ?>
                <img style="width:100%" src="../images/<?= $photo ?>" alt="...">
              </div>
              <div class="card-footer text-center bg-whitesmoke">
                <a href="#" class="btn btn-icon icon-left btn-info" data-toggle="modal" data-target="#photoModal<?= $id ?>"><i class="fas fa-image"></i> <span>Ubah Foto</span></a>
              </div>
            </div>
          </div>
          <div class="col-12 col-sm-8 col-md-7">
            <div class="card">
              <div class="card-header">
                <h4>Data Berita</h4>
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
                <div><p><b>Judul :</b><br/>
                  <?= $row['title'] ?>
                </p></div>
                <div><p><b>Teks Konten :</b><br/>
                  <?= $row['text_content'] ?>
                </p></div>
                <div><p><b>Penulis :</b><br/>
                  <?= $row['name'] ?>
                </p></div>
                <div><p><b>Tanggal :</b><br/>
                  <?php
                    if(!empty($row['date'])) :
                      $date = $row['date'];
                      $text = 'text-success';
                    else :
                      $date = 'Belum diisi';
                      $text = 'text-danger';
                    endif;
                  ?>
                  <span class="<?=$text?>"><?=$date?><span>
                </p></div>

              </div>
              <div class="card-footer text-center bg-whitesmoke">
                <a href="#" class="btn btn-icon icon-left btn-success" data-toggle="modal" data-target="#newsModal<?= $id ?>"><i class="fas fa-image"></i> <span>Ubah Data</span></a>
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
              <h4 class="modal-title">Perbaharui Foto Berita</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="POST" action="" enctype="multipart/form-data">
              <div class="modal-body">
                <input type="hidden" id="id_news" name="id_news" value="<?= $id ?>">
                <div class="form-group text-center">
                  <?php
                    $stmt = $pdo->prepare('SELECT photo_news FROM tbl_news WHERE id_news = :id');
                    $stmt->execute([':id'=>$id]);
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);

                  if($row['photo_news'] != NULL) :
                    $photo = "news/".$row['photo_news'];
                  else:
                    $photo = "p-150.png"; 
                  endif;
                  ?>
                  <img style="width:100%" src="../images/<?= $photo ?>" alt="..." id="preview">
                </div>
                <div class="form-group">
                  <input type="file" name="photo" class="file" accept="image/*" hidden />
                  <div class="input-group my-3">
                    <input type="text" id="file" class="form-control" placeholder="Unggah foto..." disabled>
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

      <!-- Change Data News Modal Dialog -->
      <div class="modal fade" role="dialog" id="newsModal<?= $id ?>">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Ubah Data Berita</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="POST" action="">
              <div class="modal-body">
                <?php
                  $id = $_GET['id-news'];
                  $stmt = $pdo->prepare('SELECT * FROM tbl_news a JOIN tbl_user b ON
                    a.user=b.id_user WHERE id_news=:id');
                  $stmt->execute([':id'=>$id]);

                  $row = $stmt->fetch(PDO::FETCH_ASSOC);
                ?>
                <input type="hidden" id="id_news" name="id_news" value="<?= $id ?>">
                <div class="form-group">
                  <label for="title">Judul</label>
                  <input type="text" class="form-control" name="title" id="title" placeholder="Judul" value="<?= $row['title'] ?>" required autocomplete="off">
                </div>
                <div class="form-group">
                  <label for="text-content">Teks Konten</label>
                  <textarea class="form-control" name="text-content" id="text-content" placeholder="Teks Konten" style="height: 100px" required><?= $row['text_content'] ?></textarea>
                </div>
                <div class="form-group">
                  <label for="author">Penulis</label>
                  <select name="author" id="author" class="form-control" required>
                    <option value="" disabled>Pilih Penulis</option>
                    <?php
                      $query_author = "SELECT * FROM tbl_user";
                      $author = $pdo->query($query_author);

                      foreach ($author as $value) :
                        if($row['user'] == $value['id_user']) :
                          $select = "selected";
                        else :
                          $select = "";
                        endif;
                        echo "<option value='".$value['id_user']."'>".$value['name']."</option>";
                      endforeach;
                    ?>
                  </select>
                </div>
                <div class="form-group">
                  <label for="date-authored">Tanggal</label>
                  <input type="date" class="form-control" name="date-authored" id="date-authored" autocomplete="off" value="<?= $row['date'] ?>" max="<?= date('Y-m-d') ?>">
                </div>

              </div>
              <div class="modal-footer bg-whitesmoke br">
                <button type="submit" name="edit_news" id="edit_news" class="btn btn-icon icon-left btn-success"><i class="fas fa-save"></i> <span>Simpan Perubahan</span></button>
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