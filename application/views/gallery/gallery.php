<div id="content" class="col-xs-12 col-md-10 ">
    <div class="row chapeau">
        <div class="col-xs-7  col-md-7">
            <h1> <?= _('Gallerie') ?></h1>
        </div>
        <div class="col-xs-2  col-md-2">
            <form id="filter" action="../../gallery/view/" method="get" class="form-inline" style="margin-top:1.5em">
                <label><?= _('Classe: ') ?></label>
                <div class="input-group">
                    <select class="form-control input-sm" name="classe" onchange="this.form.project.value = '';this.form.submit();">
                        <option value=""><?= _('Toutes')?></option>
                        <?php foreach($classes as $classe): ?>
                            <?= '<option value="' . $classe . '"' . (@$_GET['classe'] === $classe ? 'selected' : '') . '>' . $classe . '</option>' . "\n" ?>
                        <?php endforeach?>
                    </select>
                </div>
            </div>
            <div class="col-xs-3  col-md-3">
                <div class="form-inline" style="margin-top:1.5em">
                    <label><?= _('Projet: ') ?></label>
                    <div class="input-group">
                        <select class="form-control input-sm" name="project" onchange="this.form.submit()">
                            <option value=""><?= _('Tous')?></option>
                            <?php foreach($projects as $project): ?>
                                <?= '<option value="' . $project->project_id . '"' . (@$_GET['project'] === $project->project_id ? 'selected' : '') . '>' . $project->project_name . '</option>' . "\n" ?>
                            <?php endforeach?>
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div> <!-- /. ROW -->
    <div class="text-center">
        <ul class="pagination pagination-sm">
            <?= $this->pagination->create_links(); ?>
        </ul>
    </div><!-- /. row -->

    <?php $curr = 0; $tmp_project = ''; ?>

    <?php foreach ($medias as $media): ?>
        <?php if($tmp_project !== $media->project_name): ?>

            <?php $tmp_project = $media->project_name;
            echo'<h3>' . $media->project_name . '</h3>' ?>
            <div class="row" style = "margin-top:1em">
            <?php endif ?>

            <div class="col-lg-3 col-md-4 col-xs-12 text-center" style = "margin-bottom:1em">
                <?php if ($media->extension === 'mp4' || $media->extension ===  'mov' || $media->extension === 'avi'):?>
                    <div class="thumbnail" style="margin-bottom:0px">
                        <div class="embed-responsive embed-responsive-16by9 " >
                            <video class="embed-responsive-item thumbnail-180" preload="metadata" controls>
                                <source src="<?= $media->file?>" type="video/mp4">
                            </video>
                        </div>

                        <?php elseif($media->extension  === 'jpeg' || $media->extension  === 'jpg' || $media->extension  === 'png' || $media->extension  === 'gif'): ?>
                            <div class="thumbnail">
                                <a data-lightbox="projects" href="<?= $media->file  ?>"><img class="imageClip" src="<?= $media->thumbnail  ?>" alt="<?= $media->name  ?>" /></a>

                            <?php endif ?>

                            <p class="caption" style="margin:0"><small><a href="<?= $media->file ?>"><?= '@' . $media->name . ' ' . $media->last_name . ' <small>(' . $media->school_year . ')</small>' ?></a></small></p>
                        </div>
                    </div> <!-- /. col -->

                    <?php

                    if ($medias[$curr]->project_name !== @$medias[$curr + 1]->project_name)
                    {
                        echo '</div><!-- /. row -->';
                    }
                    $curr++;
                    ?>
                <?php endforeach ?>
                <div class="text-center">
                    <ul class="pagination pagination-sm">
                        <?= $this->pagination->create_links(); ?>
                    </ul>
                </div><!-- /. row -->

            </div> <!-- /. Content -->



            <script src="/assets/js/lightbox.js"></script><!-- lightbox -->
            <script>
            lightbox.option({
                'resizeDuration': 200,
                'wrapAround': true,
                'fitImagesInViewport':true
            })
            </script> <!-- lightbox -->
