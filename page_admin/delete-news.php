<?php

require './function.php';

if(isset($_GET["id-news"])) :
    if (delete_news($_GET) > 0) :
    echo "
        <script>
        alert('Data berita berhasil dihapus!');
        document.location.href = './data-news.php';
        </script>
        ";
    else :
        echo "
            <script>
            alert('Data berita gagal dihapus!');
            document.location.href = './data-news.php';
            </script>
        ";
    endif;
endif;

?>