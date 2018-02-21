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
	<link rel="stylesheet" type="text/css" href="/assets/js/calendar/css/bootstrap-datepicker.standalone.css" />
	<link rel="stylesheet" type='text/css' href="/assets/css/styles.css"/>
	<link rel="stylesheet" type='text/css' href="/assets/css/fonts.css"/>
	<!--	<link href="https://fonts.googleapis.com/css?family=Roboto:100,100italic,300,300italic,400,400italic,500,500italic,700,700italic,900,900italic" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Coming+Soon|Indie+Flower" rel="stylesheet"> -->

	<script src="/assets/js/jquery-3.1.1.min.js"></script>
	<script src="/assets/js/scripts.js"></script>

	<?php if (isset($page_title)): ?>
		<title>MediaGrade: <?= htmlspecialchars($page_title) ?></title>
	<?php else: ?>
		<title>MediaGrade</title>
	<?php endif ?>

	<?php if( ! isset($_SESSION['logged_in'])): ?>
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
	<?php if( ! isset($_SESSION['logged_in'])): ?>
		<?php if(substr($random_media, -3) === 'mp4') : ?>
			<video autoplay loop id="bgvid" muted>
				<source src="<?= $random_media ?>" type="video/mp4">
				</video>
			<?php endif ?>
		<?php endif ?>
		<div class="container-fluid">
			<?php if(isset($_SESSION['logged_in'])): ?>
			<header class="row row-eq-height hidden-print">

				<!-- Logo -->
				<div class="col-xs-2 col-md-2 bg-primary" style="border-bottom:#34495e solid 1px;padding:0.5em 0">
					<img src="/assets/img/logo_white.png"  alt="MediaGrade" class="center-block img-circle img-responsive" style="text-align:center;width:75px">
				</div>

				<!-- title -->
				<div class="col-xs-8 col-md-8" >
					<h1><?= @$page_title ?></h1>
					<p class="muted"><?= $this->session->first_name . ' ' . $this->session->last_name ?>, <?= ($this->Users_model->isAdmin() ? _('Professeur') : $this->session->class_name) ?></p>
				</div>

				<!-- Avatar -->
				<div class="col-xs-2 col-md-2" >
					<div id="profile-menu">
						<img src="<?=(empty($this->session->avatar) ? '/assets/img/default_avatar.jpg' : $this->session->avatar )?>"  alt="user_avatar" class="center-block img-circle img-responsive" style="text-align:center;width:75px;margin-top:.5em">
						<a href="/profile">
							<span class="glyphicon glyphicon-user"> </span>
						</a>
						<a href="/logout">
							<span class="glyphicon glyphicon-log-out"> </span>
						</a>
					</div>
				</div>
			</header>
		<?php endif ?>
			<div class="row" id="body-row">
