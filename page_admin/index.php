<?php
session_start();
if(!isset($_SESSION['user'])) :
  header("Location: ../index.php");
  exit();
endif;
$page = "Desa Tukamasea";
include './include/heading.php';

$count_user = "SELECT COUNT(*) AS count FROM tbl_user";
$count_news = "SELECT COUNT(*) AS count FROM tbl_news";
$count_product = "SELECT COUNT(*) AS count FROM tbl_product";
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
        </div>
        <div class="row">
          <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
              <div class="card-icon bg-warning">
                <i class="fas fa-users"></i>
              </div>
              <div class="card-wrap">
                <div class="card-header">
                  <h4>Pengguna</h4>
                </div>
                <div class="card-body">
                  <?php
                  $result = $pdo->query($count_user);
                  $row = $result->fetch(PDO::FETCH_ASSOC);
                  echo $row['count'];
                  ?>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
              <div class="card-icon bg-primary">
                <i class="fas fa-address-card"></i>
              </div>
              <div class="card-wrap">
                <div class="card-header">
                  <h4>Berita</h4>
                </div>
                <div class="card-body">
                  <?php
                  $result = $pdo->query($count_news);
                  $row = $result->fetch(PDO::FETCH_ASSOC);
                  echo $row['count'];
                  ?>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
              <div class="card-icon bg-success">
                <i class="fas fa-map-marked-alt"></i>
              </div>
              <div class="card-wrap">
                <div class="card-header">
                  <h4>Produk</h4>
                </div>
                <div class="card-body">
                  <?php
                  $result = $pdo->query($count_product);
                  $row = $result->fetch(PDO::FETCH_ASSOC);
                  echo $row['count'];
                  ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
    
    <?php
    include './include/footer.php';
    ?>
  </div>
</div>

<?php
include './include/script.php';
?>