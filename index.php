<?php
include './include/header.php';
?>

<!-- home section starts  -->

<section class="home" id="home">
    <div class="content">
        <h3 data-aos="fade-up" data-aos-delay="300">Desa Tukamasea</h3>
        <a data-aos="fade-up" data-aos-delay="600" href="#" class="btn">KUNJUNGI SEKARANG</a>
    </div>
</section>

<!-- home section ends -->

<!-- about section starts  -->

<section class="about" id="about">

    <div class="video-container" data-aos="fade-right" data-aos-delay="300">
        <video src="./assets/images/vid1.mp4" muted autoplay loop class="video"></video>
        <div class="controls">
            <span class="control-btn" data-src="./assets/images/vid1.mp4"></span>
            <span class="control-btn" data-src="./assets/images/vid1.mp4"></span>
            <span class="control-btn" data-src="./assets/images/vid1.mp4"></span>
        </div>
    </div>

    <div class="content" data-aos="fade-left" data-aos-delay="600">
        <h3>Profil Desa</h3>
        <p>Tukamasea (Lontara Bugis & Lontara Makassar: ᨈᨘᨀᨆᨔᨙᨕ, transliterasi: Tukamaséa) adalah nama sebuah desa yang berada di wilayah Kecamatan Bantimurung, Kabupaten Maros, Provinsi Sulawesi Selatan, Indonesia. Desa Tukamasea berstatus sebagai desa definitif dan tergolong pula sebagai desa swadaya.</p>
        <a href="./profil.php" class="btn">Selengkapnya</a>
    </div>

</section>

<!-- about section ends -->

<!-- destination section starts  -->

<section class="destination" id="destination">

    <div class="heading">
        <h1>Kategori</h1>
    </div>

    <div class="box-container">

    <?php
    $result = $pdo->query("SELECT * FROM tbl_product");
    foreach ($result as $row) :
    ?>
        <div class="box" data-aos="fade-up" data-aos-delay="150">
            <?php
            $galleries = unserialize($row['galleries']);
            // foreach ($galleries as $gallery) :
            ?>
            <div class="image">
                <img src="./images/products/<?= $galleries[0] ?>" alt="">
            </div>
            <?php
            // endforeach;
            ?>
            <div class="content">
                <h3><?= $row['category'] ?></h3>
                <p>Kunjungi <?= $row['category'] ?> Yang Ada di Desa Tukamasea.</p>
                <a href="./produk.php?id-product=<?= $row['id_product'] ?>">Selengkapnya<i class="fas fa-angle-right"></i></a>
            </div>
        </div>
    <?php
    endforeach;
    ?>

        <div class="box" data-aos="fade-up" data-aos-delay="300">
            <div class="image">
                <img src="./assets/images/des-2.jpg" alt="">
            </div>
            <div class="content">
                <h3>Kuliner</h3>
                <p>Rasakan Kuliner Yang Ada di Desa Tukamasea.</p>
                <a href="./kuliner.php">Selengkapnya<i class="fas fa-angle-right"></i></a>
            </div>
        </div>

    </div>

</section>

<!-- destination section ends -->

<?php
    include './include/footer.php';
?>