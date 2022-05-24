<?php
session_start();
$session = $_SESSION['user']['level'];
$level = ['Admin'];
if(!in_array($session, $level)) :
  echo "Halaman gagal ditampilkan.";
  exit();
endif;

$page = "Tambah Data Pengguna";
include './include/heading.php';

//cek apakah tombol submit sudah ditekan atau belum
if(isset($_POST["submit"])) :
  add_user($_POST);
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
          <div class="breadcrumb-item active"><a href="./index.php">Dashboard</a></div>
          <div class="breadcrumb-item"><?= $page ?></div>
        </div>
        </div>
      <div class="card">
        <div class="card-body">
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
          <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
              <label for="name">Nama</label>
              <input type="text" name="name" id="name" class="form-control" placeholder="Nama" autocomplete="off" required />
            </div>
            <div class="form-group">
              <label for="username">Nama Pengguna</label>
              <input type="text" name="username" id="username" class="form-control" placeholder="Nama Pengguna" autocomplete="off" required />
            </div>
            <div class="form-group">
              <label for="password">Kata Sandi</label>
              <input type="password" name="password" id="password" class="form-control" placeholder="Kata Sandi" autocomplete="off" required />
            </div>
            <div class="form-group">
              <label for="re-password">Konfirmasi Kata Sandi</label>
              <input type="password" name="re-password" id="re-password" class="form-control" placeholder="Konfirmasi Kata Sandi" autocomplete="off" required />
            </div>
            <div class="form-group">
              <label for="user-level">Level Pengguna</label>
              <select name="user-level" id="user-level" class="form-control" required>
                <option value="" disabled selected>Pilih Level Pengguna</option>
                <?php
                  $query_level = "SELECT * FROM tbl_level";
                  $level = $pdo->query($query_level);

                  foreach ($level as $value) :
                    echo "<option value='".$value['user_level']."'>".$value['user_level']."</option>";
                  endforeach;
                ?>
              </select>
            </div>
            <div class="form-group">
              <label for="photo">Unggah Foto</label>
              <input type="file" name="photo" class="file" accept="image/*" hidden />
              <div class="input-group my-3">
                <input type="text" id="file" class="form-control" placeholder="Unggah foto..." disabled>
                <div class="input-group-append">
                  <button type="button" id="select_photo" class="browse btn btn-icon icon-left btn-primary"><i class="fas fa-image"></i> <span class="d-sm-none d-md-inline-block">Unggah Foto</span></button>
                </div>
              </div>
              <div class="form-group">
                <img src="../images/p-150.png" alt="..." id="preview" />
              </div>
            </div>
            <div class="card-footer text-right">
              <button type="submit" name="submit" class="btn btn-icon icon-left btn-success mr-1"><i class="fas fa-save"></i> Simpan</button>
              <button type="button" class="btn btn-icon icon-left btn-warning"><i class="fas fa-times"></i> Batal</button>
            </div>
          </form>
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
      var img_prev = document.getElementById("preview");
      img_prev.src = e.target.result;
      img_prev.style.height = '150px';
      img_prev.style.width = '150px';
  };
  // read the image file as a data URL.
  reader.readAsDataURL(this.files[0]);
  });
</script>