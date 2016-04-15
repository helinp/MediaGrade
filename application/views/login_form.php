<?php if(substr($random_media, -3) === 'mp4') : ?>
<video autoplay loop id="bgvid" muted>
    <source src="<?= $random_media ?>" type="video/mp4">
</video>
<?php endif ?>


<div class="vertical-center">

<main class="col-md-3 col-sm-8 text-center login">
  <h1><a href="/"><img alt="MediaGrade" src="/assets/img/logo.png" style="width:200px"/></a></h1>
  <?php echo form_open('verifylogin'); ?>
        <fieldset>
            <div class="form-group">
                <input autofocus class="form-control" name="username" placeholder="<?= LABEL_USERNAME ?>" type="text"/>
            </div>
            <div class="form-group">
                <input class="form-control" name="password" autocomplete="off" placeholder="<?= LABEL_PASSWORD ?>" type="password"/>
            </div>
            <?php if(!empty(validation_errors())): ?>
            <div class="alert alert-danger" role="alert">
			  <span class="sr-only">Error:</span> <?= validation_errors(); ?>
			</div>
            <?php endif ?>
            <div class="form-group">
                <button type="submit" class="btn btn-default"><?= LABEL_LOGIN ?></button>
            </div>
        </fieldset>

    </form>
    
        <?php if(DEMO_VERSION): ?>
        <h5><?= LABEL_DEMO_ACCOUNTS ?></h5>
        <code>student 123456</code><br /> <code>teacher 123456</code>
        <?php endif ?>
</main>
</div>
