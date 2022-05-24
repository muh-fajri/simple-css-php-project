<?php
include './include/header.php';
?>

<?php
$id_product = $_GET['id-product'];
$stmt = $pdo->prepare('SELECT * FROM tbl_product WHERE id_product=:id');
$stmt->execute([':id'=>$id_product]);

$row = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<section class="destination" id="destination">

    <div class="heading">
        <h1><?= $row['category'] ?></h1>
    </div>

    <div class="banner">

        <div class="content" data-aos="zoom-in-up" data-aos-delay="300">
            <h3><?= $row['product'] ?></h3>
            <h4><?= $row['sub_product'] ?></h4>
            <p><?= $row['description'] ?></p>

            <a href="https://api.whatsapp.com/send?phone=6285240597330&text=Assalamualaikum%20Warahmatullahi%20Wabarakatuh" class="btn">Pesan</a>
        </div>

    </div>

</section>

<section class="gallery" id="gallery">

    <div class="heading">
        <h1>Galeri</h1>
    </div>
    
    <div class="box-container">

        <?php
        $galleries = unserialize($row['galleries']);
        foreach ($galleries as $gallery) :
        ?>

        <div class="box" data-aos="zoom-in-up" data-aos-delay="150">
            <img src="./images/products/<?= $gallery ?>" alt="...">
        </div>

        <?php
        endforeach;
        ?>

        <!-- <div class="box" data-aos="zoom-in-up" data-aos-delay="150">
            <img src="./assets/images/21.jpg" alt="">
        </div>

        <div class="box" data-aos="zoom-in-up" data-aos-delay="450">
            <img src="./assets/images/23.jpg" alt="">
        </div>

        <div class="box" data-aos="zoom-in-up" data-aos-delay="150">
            <img src="./assets/images/25.jpg" alt="">
        </div>

        <div class="box" data-aos="zoom-in-up" data-aos-delay="300">
            <img src="./assets/images/22.jpg" alt="">
        </div>

        <div class="box" data-aos="zoom-in-up" data-aos-delay="450">
            <img src="./assets/images/20.jpg" alt="">
        </div>

        <div class="box" data-aos="zoom-in-up" data-aos-delay="150">
            <img src="./assets/images/19.jpg" alt="">
        </div> -->

    </div>

</section>
<?php
include './include/footer.php';
?>