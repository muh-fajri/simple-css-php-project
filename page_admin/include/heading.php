<?php
session_start();

require './function.php';

// Mengecek user sudah login atau belum, dengan memeriksa session-nya
if(!isset($_SESSION['user'])) {
  header('Location: index.php');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title><?= $page ?></title>

  <!-- General CSS Files -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

  <!-- CSS Libraries -->
  <link rel="stylesheet" href="./node_modules/jqvmap/dist/jqvmap.min.css">
  <link rel="stylesheet" href="./node_modules/summernote/dist/summernote-bs4.css">
  <link rel="stylesheet" href="./node_modules/owl.carousel/dist/assets/owl.carousel.min.css">
  <link rel="stylesheet" href="./node_modules/owl.carousel/dist/assets/owl.theme.default.min.css">

  <!-- Template CSS -->
  <link rel="stylesheet" href="./assets/css/style.css">
  <link rel="stylesheet" href="./assets/css/components.css">

  <!-- Sweet Alert -->
  <link rel="stylesheet" href="./assets/css/sweetalert_1-0-1.min.css">

  <!-- Sweet Alert -->
  <script src="./assets/js/sweetalert_1-0-1.min.js"></script>

  <!-- DataTables -->
  <link rel="stylesheet" href="./assets/css/jquery.dataTables.min.css">

</head>
<body>