<?php

require './function.php';

if(isset($_GET["id-product"])) :
    if (delete_product($_GET) > 0) :
    echo "
        <script>
        alert('Data produk berhasil dihapus!');
        document.location.href = './data-product.php';
        </script>
        ";
    else :
        echo "
            <script>
            alert('Data produk gagal dihapus!');
            document.location.href = './data-product.php';
            </script>
        ";
    endif;
endif;

?>