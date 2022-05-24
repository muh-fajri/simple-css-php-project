<?php
session_start();
$session = $_SESSION['user']['level'];
$level = ['Admin'];
if(!in_array($session, $level)) :
  echo "Halaman gagal ditampilkan.";
  exit();
endif;

$page = "Detail Data Produk";
include './include/heading.php';

// check whether edit_photo button has clicked
if (isset($_POST['edit_photo'])) :
  $id = $_POST["id_product"];

  // fetch image name from database
  $stmt = $pdo->prepare('SELECT galleries FROM tbl_product WHERE id_product = :id');
  $stmt->execute([':id'=>$id]);
  $row = $stmt->fetch(PDO::FETCH_ASSOC);

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
          unlink($uploadDir.$row['photo']);
          move_uploaded_file($tmpDir,$uploadDir.$imgUploadName[$i]);
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
      $imgUploadName = $row['galleries'];
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

    $stmt = $pdo->prepare('UPDATE tbl_product SET galleries = :galleries WHERE id_product = :id');
    $stmt->bindParam(':galleries', $serialize);
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

// check whether edit_product button has clicked
if (isset($_POST['edit_product'])) :
  $id = $_POST["id_product"];
  $cat = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_STRING);
  $prod = filter_input(INPUT_POST, 'product', FILTER_SANITIZE_STRING);
  $sub_prod = filter_input(INPUT_POST, 'sub-product', FILTER_SANITIZE_STRING);
  $desc = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);

  if(!isset($errMSG)) :
    $stmt = $pdo->prepare('UPDATE tbl_product SET category = :cat, product = :prod,
      sub_product = :sub_prod, description = :desc
      WHERE id_product = :id');
    $params = [
      ':cat' => $cat,
      ':prod' => $prod,
      ':sub_prod' => $sub_prod,
      ':desc' => $desc,
      ':id' => $id
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
            $id = $_GET['id-product'];
            $stmt = $pdo->prepare('SELECT * FROM tbl_product WHERE id_product=:id');
            $stmt->execute([':id'=>$id]);

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
          ?>
          <div class="col-12 col-sm-4 col-md-5">
            <div class="card">
              <div class="card-header">
                <h4>Foto Galeri</h4>
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
                if($row['galleries'] != NULL) :
                  $photos = unserialize($row['galleries']);
                  foreach($photos as $photo) :
                ?>
                  <img src="../images/products/<?= $photo ?>" alt="..." style="width:100%">
                <?php
                  endforeach;
                else :
                  $photo = "avatar-1.png";
                ?>
                  <img src="../images/products/<?= $photo ?>" alt="..." style="width:100%">
                <?php
                endif;
                ?>
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
                <div><p><b>Kategori :</b><br/>
                  <?= $row['category'] ?>
                </p></div>
                <div><p><b>Produk :</b><br/>
                  <?= $row['product'] ?>
                </p></div>
                <div><p><b>Sub-produk :</b><br/>
                  <?= $row['sub_product'] ?>
                </p></div>
                <div><p><b>Keterangan :</b><br/>
                  <?= $row['description'] ?>
                </p></div>
              </div>
              <div class="card-footer text-center bg-whitesmoke">
                <a href="#" class="btn btn-icon icon-left btn-success" data-toggle="modal" data-target="#productModal<?= $id ?>"><i class="fas fa-image"></i> <span>Ubah Data</span></a>
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
              <h4 class="modal-title">Perbaharui Foto Galeri</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="POST" action="" enctype="multipart/form-data">
              <div class="modal-body">
                <input type="hidden" id="id_product" name="id_product" value="<?= $id ?>">
                <div class="form-group text-center">
                    <?php
                      $stmt = $pdo->prepare('SELECT galleries FROM tbl_product WHERE id_product = :id');
                      $stmt->execute([':id'=>$id]);
                      $row = $stmt->fetch(PDO::FETCH_ASSOC);

                      if(!empty($row['galleries'])) :
                        $photos = unserialize($row['galleries']);
                        foreach($photos as $photo) :
                    ?>
                          <img src="../images/products/<?= $photo ?>" alt="..." id="preview" style="width: 100%">
                    <?php
                        endforeach;
                      else :
                    ?>
                        <img src="../images/p-150.png" alt="..." id="preview" style="width: 100%">
                    <?php
                      endif;
                    ?>
                </div>
                <div class="form-group">
                  <input type="file" name="galleries[]" class="galleries" accept="image/*" multiple hidden/>
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

      <!-- Change Data Product Modal Dialog -->
      <div class="modal fade" role="dialog" id="productModal<?= $id ?>">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Ubah Data Produk</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="POST" action="">
              <div class="modal-body">
                <?php
                  $id = $_GET['id-product'];
                  $stmt = $pdo->prepare('SELECT * FROM tbl_product WHERE id_product=:id');
                  $stmt->execute([':id'=>$id]);

                  $row = $stmt->fetch(PDO::FETCH_ASSOC);
                ?>
                <input type="hidden" class="form-control" name="id_product" id="id_product" value="<?= $row['id_product'] ?>" required>
                <div class="form-group">
                  <label for="category">Kategori</label>
                  <input type="text" class="form-control" name="category" id="category" placeholder="Kategori" value="<?= $row['category'] ?>" required autocomplete="off">
                </div>
                <div class="form-group">
                  <label for="product">Produk</label>
                  <input type="text" class="form-control" name="product" id="product" placeholder="Produk" value="<?= $row['product'] ?>" required autocomplete="off">
                </div>
                <div class="form-group">
                  <label for="sub-product">Sub-produk</label>
                  <input type="text" class="form-control" name="sub-product" id="sub-product" placeholder="Sub-produk" value="<?= $row['sub_product'] ?>" required autocomplete="off">
                </div>
                <div class="form-group">
                  <label for="description">Keterangan</label>
                  <textarea class="form-control" name="description" id="description" placeholder="Teks Konten" style="height: 100px" required><?= $row['description'] ?></textarea>
                </div>

              </div>
              <div class="modal-footer bg-whitesmoke br">
                <button type="submit" name="edit_product" id="edit_product" class="btn btn-icon icon-left btn-success"><i class="fas fa-save"></i> <span>Simpan Perubahan</span></button>
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
  var file = $(this).parents().find(".galleries");
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