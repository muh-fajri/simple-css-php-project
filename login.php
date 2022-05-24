<?php
require_once("connection.php");

// check whether login button has clicked
if (isset($_POST['login'])) :
  $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
  $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

  // Check whether the user has input login data
  if(empty($username)) :
    $errMSG = "Nama pengguna belum diisi.";
    setcookie("user_fail", $errMSG, time()+5);
    header("Location: ".$_SERVER['PHP_SELF']);
  elseif(empty($password)) :
    $errMSG = "Kata sandi belum diisi.";
    setcookie("user_fail", $errMSG, time()+5);
    header("Location: ".$_SERVER['PHP_SELF']);
  endif;

  // Check whether data is submitted exist in database
  if(!isset($errMSG)) :
    $query = "SELECT * FROM tbl_user WHERE username=:username AND password=:password";
    $stmt = $pdo->prepare($query);
    $params = [":username" => $username, ":password" => $password];
    $stmt->execute($params);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!empty($row)) :
      if(password_verify($password, $row["password_hash"])) :
          session_start();
          // create session named "user"
          $_SESSION["user"] = $row;
          // delete cookie message
          setcookie("user_fail", "delete", time()-1);
          if($row["level"] == "Admin") :
            header("Location: page_admin/");
          else :
            header("Location: page_user/");
          endif;
      endif;
    else :
      setcookie("user_fail", "Nama pengguna atau kata sandi tidak sesuai.", time()+5);
      header("Location: ".$_SERVER['PHP_SELF']);
    endif;
  endif;
endif;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Desa Tukamasea</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="./assets/css/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />

    <!-- General CSS Files -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">

    <!-- custom js file link  -->
    <script src="./assets/js/script.js" defer></script>

</head>
    
<body>

<!-- header section starts  -->
    
<header class="header">

    <a data-aos="zoom-in-left" data-aos-delay="150" href="./index.php" class="logo"></i>Desa Tukamasea </a>

</header>

<section class="section mt-5">
  <div class="container mt-5">
    <div class="row">
      <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">

        <div class="card card-primary">
          <div class="card-header bg-primary text-center"><h4>Halaman Login</h4></div>              
          <div class="card-body">
            <form method="POST" action="">
              <?php
                // Check whether there is a cookie named "user_fail"
                if(isset($_COOKIE['user_fail'])) :
                  echo" <div class='alert alert-danger alert-dismissible show fade'>
                    <div class='alert-body'>
                      <button class='close' data-dismiss='alert'>
                        <span>&times;</span>
                      </button>".
                      $_COOKIE['user_fail'].
                    "</div>
                  </div>";
                endif;
              ?>
              <div class="form-group">
                <label for="username">Nama Pengguna</label>
                <input id="username" type="text" class="form-control" name="username" tabindex="1" autofocus autocomplete="off">
                <!-- <div class="invalid-feedback">
                  Tolong masukkan username
                </div> -->
              </div>

              <div class="form-group">
                <div class="d-block">
                  <label for="password" class="control-label">Kata Sandi</label>
                </div>
                <input id="password" type="password" class="form-control" name="password" tabindex="2"autocomplete="off">
                <!-- <div class="invalid-feedback">
                  Tolong masukkan kata sandi
                </div> -->
              </div>

              <div class="form-group">
                <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4" name="login">
                  Masuk
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- footer section starts  -->

<div class="credit">Tugas <span>Rekayasa Perangkat Lunak</span> | Desa Tukamasea</div>

<!-- footer section ends -->

<!-- General JS Scripts -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>

<script>
    AOS.init({
        duration: 800,
        offset:150,
    });
</script>

</body>
</html>