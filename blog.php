<?php
include './include/header.php';
?>

<!-- blogs section starts  -->

<section class="blogs" id="blogs">

    <div class="heading" data-aos="zoom-in-up" data-aos-delay="300">
        <!-- <span>blogs & posts</span> -->
        <h1>Blog Kami</h1>
    </div>

    <div class="box-container">
        <?php
            $result = $pdo->query("SELECT * FROM tbl_news a JOIN tbl_user b ON
                a.user = b.id_user");
        foreach ($result as $row) :
        ?>
        <div class="box" data-aos="fade-up" data-aos-delay="150">
            <div class="image">
                <img src="./images/news/<?= $row['photo_news'] ?>" alt="...">
            </div>
            <div class="content">
                <a href="./blog-detail.php?id-news=<?= $row['id_news'] ?>" class="link"><?= $row['title'] ?></a>
                <p><?= $row['text_content'] ?></p>
                <div class="icon">
                    <a href="#"><i class="fas fa-clock"></i> <?php $date = !empty($row['date']) ? $row['date'] : 'Belum terisi'; echo $date; ?></a>
                    <a href="#"><i class="fas fa-user"></i> oleh <?= $row['name'] ?></a>
                </div>
            </div>
        </div>
        <?php
        endforeach;
        ?>

    </div>

</section>

<!-- blogs section ends -->
<?php
    include './include/footer.php';
?>