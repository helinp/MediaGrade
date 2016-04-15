
<main class="col-md-9 col-lg-10" style="padding-top:2em;">
    <?php foreach ($medias as $media): ?>
    <div class="col-lg-3 col-md-4 col-xs-6 text-center" style = "margin-bottom:10px">
        <?php if ($media->extension === 'mp4' || $media->extension ===  'mov' || $media->extension === 'avi'):?>
        <div class="thumbnail thumbnail-180  text-center" style="margin-bottom:0px">
            <div class="embed-responsive embed-responsive-16by9 " style="margin-bottom:-1.30em;height:90%;">
                <video class="embed-responsive-item thumbnail-180" style="height:90%" preload="metadata" controls>
                    <source src="<?= $media->file?>" type="video/mp4">
                    <p><?= LABEL_NO_HTML5_VIDEO ?> <a href="<?= $media->file ?>"><?= LABEL_HERE ?></a></p>
                </video>
            </div>
        </div>
        <?php elseif($media->extension  === 'jpeg' || $media->extension  === 'jpg' || $media->extension  === 'png' || $media->extension  === 'gif'): ?>
        <div class="thumbnail thumbnail-180 text-center" style="margin-bottom:0px">
            <img style="height:90%;" src="<?= $media->thumbnail  ?>" alt="<?= $media->name  ?>" />
        </div>
        <?php endif ?>
        <a href="<?= $media->file ?>"><?= $media->name  . ' ' . $media->last_name  . ' #' . $media->project_name ?></a>
    </div>
    <?php endforeach ?>
</main>
