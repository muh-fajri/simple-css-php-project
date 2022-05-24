      <div class="main-sidebar">
        <aside id="sidebar-wrapper">
          <div class="sidebar-brand">
            <a href="index.php">Desa Tukamasea</a>
          </div>
          <div class="sidebar-brand sidebar-brand-sm">
            <a href="index.php">DT</a>
          </div>
          <ul class="sidebar-menu">
              <li class="menu-header">Menu Utama</li>
              <li <?php if($page == "Halaman Utama") echo "class='active'"; ?>><a class="nav-link" href="index.php"><i class="fas fa-home"></i> <span>Halaman Utama</span></a></li>
              <li <?php if($page == "Profil") echo "class='active'"; ?>><a class="nav-link" href="profile.php"><i class="fas fa-user"></i> <span>Profil</span></a></li>
              <?php if(in_array($_SESSION['user']['level'], ['Admin','Operator'])) : ?>
              <li class="menu-header">Data Master</li>
              <li <?php if($page == "Data Level Pengguna") echo "class='active'"; ?>><a class="nav-link" href="data-level.php"><i class="fas fa-address-card"></i> <span>Data Level</span></a></li>
              <li <?php if($page == "Data Pengguna") echo "class='active'"; ?>><a class="nav-link" href="data-user.php"><i class="fas fa-users"></i> <span>Data Pengguna</span></a></li>
              <li <?php if($page == "Data Berita") echo "class='active'"; ?>><a class="nav-link" href="data-news.php"><i class="fas fa-address-card"></i> <span>Data Berita</span></a></li>
              <li <?php if($page == "Data Produk") echo "class='active'"; ?>><a class="nav-link" href="data-product.php"><i class="fas fa-map-marked-alt"></i> <span>Data Produk</span></a></li>
              <?php endif; ?>
          </ul>
        </aside>
      </div>
