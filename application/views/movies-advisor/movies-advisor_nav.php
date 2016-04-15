

<aside id="projects" class="col-md-3 col-lg-2 sidebar-wrapper p_sidemenu">
    <?php if(isset($_SESSION['id'])):?>
    <?php if( ! empty($_SESSION['avatar'])):?>
    <div style="margin: 0 auto;">
        <img src="<?= $_SESSION['avatar']?>" alt="user_avatar" class=" center-block img-circle img-responsive" style="text-align:center;width:100px" />
    </div>
    <?php endif ?>
    <?php endif ?>
    <form action="/movies_advisor/search" method="post">
        <div class="form-group">
            <h4><?= _('Recommander un film')?></h4>
            <label for="movie_vote"><?= _('1 - Recherchez le film')?></label>
            <input class="form-control input-sm" type="text" name="movie-name" placeholder=" <?= _('Nom du film')?>">
            <input class="form-control input-sm"type="text" name="movie-year" placeholder=" <?= _('Année de sortie')?>">

            <label for="movie-id"><small><?= _('ou')?></small></label>
            <input class="form-control input-sm" type="text"  name="movie-id" placeholder=" <?= _('IMDB ID')?>">
        </div>
        <button type="submit" name="search" class="btn btn-primary btn-sm"><?= _('Chercher les infos') ?></button>
        <hr />
    </form>
        <?php if (isset($movie_info['title'])): ?>

            <img alt="<?= $movie_info['title']?>" src="<?= $movie_info['poster_path']?>" />

            <hr />
    <?php endif ?>

    <form action="/movies_advisor/vote" method="post">
        <div class="form-group">
            <label for="movie_vote"><?= _('2 - Lui attribuer une note')?></label>
            <select class="form-control input-sm" name="movie-vote">
                <option>1 (<?= _('Pas prise de tête')?>)</option>
                <option>2 (<?= _('Bon pour sa culture générale')?>)</option>
                <option>3 (<?= _('À voir absolument')?>)</option>
                <option>4 (<?= _('Monumental')?>)</option>
            </select>
        </div>
        <button type="submit"  name="vote" class="btn btn-primary btn-sm"><?= _('Valider le choix') ?></button>
    </form>
</aside>
