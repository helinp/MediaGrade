<!DOCTYPE html>

<html lang="<?= LANG ?>">

    <head>
        <meta charset="UTF-8">

        <link href="/assets/css/bootstrap.css" rel="stylesheet"/>
    <!--    <link href="/css/bootstrap-theme.min.css" rel="stylesheet"/>-->
        <link href="/assets/css/filedrop.css" rel="stylesheet" />
        <link href="/assets/css/styles.css" rel="stylesheet"/>

        <link rel="stylesheet"  type="text/css" href="/assets/css/datepicker.min.css" />
        <link rel="stylesheet"  type="text/css" href="/assets/css/datepicker3.min.css" />

        <link href='/assets/fonts/roboto.woff2' rel='stylesheet' type='text/css'>

        <?php if (isset($title)): ?>
            <title>MediaGrade: <?= htmlspecialchars($title) ?></title>
        <?php else: ?>
            <title>MediaGrade</title>
        <?php endif ?>


        <script src="/assets/js/jquery-1.10.2.min.js"></script>
        <script src="/assets/js/validator.min.js"></script>
        <script src="/assets/js/bootstrap.min.js"></script>

        <!-- http://underscorejs.org/ -->
        <script src="/assets/js/underscore-min.js"></script>

        <!-- https://github.com/twitter/typeahead.js/ -->
        <script src="/assets/js/typeahead.jquery.js"></script>
        <script src="/assets/js/bloodhound.js"></script>
        <script src="/assets/js/scripts.js"></script>

        <?php if(!isset($_SESSION['id'])): ?>
          <?php if(substr(@$random_media, -3) === 'jpg') : ?>
            <style>html {
            background: url(<?= $random_media ?>) no-repeat center center fixed;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
          }</style>
          <?php endif ?>
        <?php endif ?>

    </head>

    <body>


    <?php if( ! isset($message)) include 'menu_top.php' ?>


    <div class="container-fluid" style="padding-left: 0px;">
