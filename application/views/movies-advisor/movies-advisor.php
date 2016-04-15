<main class="col-lg-10 col-md-9" id="content">
    <h2><?= _('On te recommande de regarder...')?></h2>
    <hr style="margin-top:0;" />
    <?php if(isset($content)): ?>
    <?php foreach ($content as $film): ?>
    <div class="col-lg-2 col-md-3 col-xs-5 text-center" style = "margin-bottom:10px">
        <div class="thumbnail text-center" style="margin-bottom:0px">
            <a href="#" data-toggle="popover" data-placement="left" title="<?= $film->title?> (<?= $film->original_title?>), <?= $film->year?>" data-content="<?= word_limiter($film->overview, 60) ?>">
                <img style="width: 100%" src="<?= $film->poster_path?>" alt="<?= $film->title?>" />
            </a>
        </div>
        <p><?= $film->avg_vote?>/4 (<?= $film->num_vote . ($film->num_vote > 1 ? _(' votes') : _(' vote')) ?>)</p>
        <!-- <form action="/movies_advisor/voteup" method="POST"><button type="submit" name="id_maf" value="<?= $film->maf_id ?>"></button></form>-->
    </div>
    <?php endforeach ?>
    <?php else: ?>
    <p><?= _("Malheureusement, aucun film n'a encore été recommandé.") ?></p>
    <?php endif ?>
</main>
<script>
$(function () {
  $('[data-toggle="popover"]').popover( {trigger: "hover"})
})
</script>
