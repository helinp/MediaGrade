<!DOCTYPE html>
<html lang="<?= LANG ?>">
	<head>
        <meta charset="UTF-8">

        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
        <link rel='shortcut icon' type='image/x-icon' href='/favicon.ico' />

        <link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.min.css" />
        <link rel="stylesheet" type='text/css' href="/assets/css/lightbox.css" />
        <link rel="stylesheet" type="text/css" href="/assets/css/datepicker.min.css" />
        <link rel="stylesheet" type="text/css" href="/assets/css/datepicker3.min.css" />
        <link rel="stylesheet" type='text/css' href="/assets/css/styles.css"/>

        <script src="/assets/js/jquery-3.1.1.min.js"></script>
        <script src="/assets/js/scripts.js"></script>

        <?php if (isset($title)): ?>
        <title>MediaGrade: <?= htmlspecialchars($title) ?></title>
        <?php else: ?>
        <title>MediaGrade</title>
        <?php endif ?>

        <?php if(!isset($_SESSION['id'])): ?>
        <?php if(substr(@$random_media, -3) === 'jpg') : ?>
        <style>
		body
		{
            background: url(<?= $random_media ?>) no-repeat center center fixed;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
        }
		</style>
        <?php endif ?>
         <style>
        .container-fluid, body
		{
              background-color: rgba(66, 86, 158, 0.6);
              height: 100vh;
        }
        </style>
        <?php endif ?>
    </head>

    <body>
    <?php if(!isset($_SESSION['id'])): ?>
      <?php if(substr($random_media, -3) === 'mp4') : ?>
  		<video autoplay loop id="bgvid" muted>
    		<source src="<?= $random_media ?>" type="video/mp4">
    	</video>
    	<?php endif ?>
 	<?php endif ?>

    <div class="container-fluid">
    <?php if(@$this->session->id && $this->uri->segment(2) !== 'project_management'): ?>
        <header class="row row-eq-height hidden-print">
            <div class="col-xs-2 col-md-2 bg-primary" style="border-bottom:#34495e solid 1px;padding:0.5em 0">
                <img src="/assets/img/logo_white.png"  alt="MediaGrade" class="center-block img-circle img-responsive" style="text-align:center;width:75px">
            </div>
            <div class="col-xs-2 col-md-1" >
                <img src="<?=(empty($this->session->avatar) ? '/assets/img/default_avatar.jpg' : $this->session->avatar )?>"  alt="user_avatar" class="center-block img-circle img-responsive" style="text-align:center;width:75px;margin-top:.5em">
            </div>
            <div class="col-xs-6 col-md-7" >
                <h2 class="muted username-title"><?= $this->session->name . ' ' . $this->session->last_name ?></h2>
                <p class="muted"><?= ($this->session->role === 'admin' ? _('Professeur') : _('Élève de ') . $this->session->class) ?> - <a href="<?= base_url(); ?>profile"><?= _('Modifier mon profil') ?></a></p>
            </div>
            <div class="col-xs-2 col-md-2" >
                <p class="bottom-align-text " ><a href="../../logout" class="pull-right"><span class="glyphicon glyphicon-log-out"></span> <?=_('Déconnection')?></a></p>
            </div>
        </header>
    <?php endif?>

        <div class="row">
