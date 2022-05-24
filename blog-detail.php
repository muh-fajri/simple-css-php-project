<?php
include './include/header.php';
?>

<!-- blogs section starts  -->

<section class="blogs" id="blogs">

    <div class="heading" data-aos="zoom-in-up" data-aos-delay="300">
        <?php
            $id = $_GET['id-news'];
            $stmt = $pdo->prepare('SELECT * FROM tbl_news a JOIN tbl_user b ON
                a.user=b.id_user WHERE id_news = :id');
            $stmt->execute([':id'=>$id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
        ?>
        <h1><?= $row['title'] ?></h1>
    </div>

    <div class="box-container">
        <div class="box p-5 bg-white text-black" data-aos="fade-up" data-aos-delay="150">
            <div class="image" style="height:500px">
                <img src="./images/news/<?= $row['photo_news'] ?>" alt="...">
            </div>
            <div class="content">
                <a href="#" class="link text-primary"><?= $row['title'] ?></a>
                <div class="icon">
                    <a href="#"><i class="fas fa-clock"></i> <?php $date = !empty($row['date']) ? $row['date'] : 'Belum terisi'; echo $date; ?></a>
                    <a href="#"><i class="fas fa-user"></i> oleh <?= $row['name'] ?></a>
                </div>
                <div class="pt-5 pb-0 rounded">
                    <p><?= $row['text_content'] ?></p>
                </div>
            </div>
        </div>
    </div>

</section>

<!-- blogs section ends -->
<?php
    include './include/footer.php';
?>