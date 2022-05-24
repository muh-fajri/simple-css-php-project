<?php

require './function.php';

if(isset($_GET["id-level"])) :
    if (delete_level($_GET) > 0) :
    echo "
        <script>
        alert('Data level pengguna berhasil dihapus!');
        document.location.href = './data-level.php';
        </script>
        ";
    else :
        echo "
            <script>
            alert('Data level pengguna gagal dihapus!');
            document.location.href = './data-level.php';
            </script>
        ";
    endif;
endif;

?>