<?php
session_start();
$session = $_SESSION['user']['level'];
$level = ['Admin'];
if(!in_array($session, $level)) :
  echo "Halaman gagal ditampilkan.";
  exit();
endif;

$page = "Data Level Pengguna";
include './include/heading.php';
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
        <?php if(in_array($session, $level)) : ?>
        <div class="card-header">
          <a href="./add-level.php" class="btn btn-icon icon-left btn-primary"><i class="fas fa-plus"></i> Tambah</a>
        </div>
        <?php endif; ?>
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
          <div class="table-responsive">
            <table class="table table-striped">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Level Pengguna</th>
                <?php if(in_array($session, $level)) : ?>
                <th scope="col">Opsi</th>
                <?php endif; ?>
              </tr>
            </thead>
            <tbody>
              <?php
                $no = 1;
                $result = $pdo->query("SELECT * FROM tbl_level");
                foreach ($result as $row) :
              ?>
              <tr>
                <th scope="row"><?= $no++; ?></th>
                <td scope="row"><?= $row['user_level']; ?></td>
                <?php if(in_array($session, $level)) : ?>
                <td scope="row">
                  <a href="./edit-level.php?id-level=<?= $row['id_level'] ?>" class="btn btn-icon icon-left btn-primary"><i class="fas fa-edit"></i> <span class="d-none d-sm-none d-lg-inline-block">Detail</span></a>
                  <?php if(in_array($session, ['Admin'])) : ?>
                  <a href="./delete-level.php?id-level=<?= $row['id_level'] ?>" class="btn btn-icon icon-left btn-danger" onclick="return confirm('Yakin ingin menghapus data pengguna?');"><i class="fas fa-trash-alt"></i> <span class="d-none d-sm-none d-lg-inline-block">Hapus</span></a>
                  <?php endif; ?>
                </td>
                <?php endif; ?>
              </tr>
              <?php endforeach; ?>
            </tbody>
            </table>
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