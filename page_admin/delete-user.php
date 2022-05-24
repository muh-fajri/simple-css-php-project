<?php

include '../function/connection.php';

if(isset($_GET["id-user"])) :
    if (delete_user($_GET) > 0) :
    echo "
        <script>
        alert('Data pengguna berhasil dihapus!');
        document.location.href = './data-user.php';
        </script>
        ";
    else :
        echo "
            <script>
            alert('Data pengguna gagal dihapus!');
            document.location.href = './data-user.php';
            </script>
        ";
    endif;
endif;

?>