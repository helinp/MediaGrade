<?php if($this->session->userdata('id')): ?>
<nav class="navbar navbar-default navbar-fixed-top" id="menu" <?= ($this->session->userdata('role') === 'admin' ? 'style="background-color:#482084;"' : 'style="background-color:#2264bb;"') ?>>
    <!-- <img class="navbar-brand" alt="MediaGrade" src="/img/logo.png" />-->
    <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand visible-xs" href="#">MediaGrade</a>
    </div>
    <div id="navbar" class="navbar-collapse collapse">
        <ul class="nav navbar-nav">
            <?php if($this->session->userdata('role') === 'admin'):?>
            <li><a href="/admin/"><span class="glyphicon glyphicon-dashboard"></span> <?= _('Dashboard') ?> </a></li>
            <li><a href="/admin/projects"><span class="glyphicon glyphicon-film"></span> <?= LABEL_MANAGE_PROJECTS ?> </a></li>
            <li><a href="/admin/grade"><span class="glyphicon glyphicon-list-alt"></span> <?= LABEL_RATE ?></a></li>
            <li>
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-th-list"></span>  <?= LABEL_RESULTS ?>  <span class="caret"></span></a>
                <ul class="dropdown-menu dropdown-menu">
                    <?php foreach($classes as $class): ?>
                    <li><a href="/admin/results/<?= trim($class, " ") ?>"><?= $class ?></a></li>
                    <?php endforeach ?>
                    <li class="divider"></li>
                    <li><a href="/admin/export"><span class="glyphicon glyphicon-export"></span> Exporter </a></li>
                </ul>
            </li>
            <?php else: ?>
            <li><a href="/projects"><span class="glyphicon glyphicon-facetime-video"></span> <?= _('Projets') ?></a></li>
            <li><a href="/gradebook"><span class="glyphicon glyphicon-list-alt"></span> <?=LABEL_GRADE_BOOK ?></a></li>
            <li><a href="/gallery/my"><span class="glyphicon glyphicon-briefcase"></span> <?= _('Ma gallerie') ?></a></li>
            <?php endif ?>
            <li><a href="/gallery"><span class="glyphicon glyphicon-sunglasses"></span> <?=LABEL_GALLERY ?></a></li>
            <li><a href="/movies_advisor"><span class="glyphicon glyphicon-film"></span> <?= _('Movies Advisor') ?></a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <?php if($this->session->userdata('role') === 'admin'):?>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-cog"></span> <?=LABEL_CONFIG ?> <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="/admin/skills"><?= LABEL_SKILLS ?></a></li>
                    <li><a href="/admin/users"><?= LABEL_CLASS_ROLL ?></a></li>
                    <li><a href="/admin/periodes"><?= _('Périodes') ?></a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="/admin/settings"><?= _('Système')?></a></li>
                </ul>
            </li>
            <?php endif ?>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span> <?= $this->session->userdata('name') . " " . $this->session->userdata('last_name') ?> <span class="caret"></span></a>
                <ul class="dropdown-menu dropdown-menu-right">
                    <li><a href="/profile"><?= LABEL_MY_PROFILE ?></a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="/logout"><span class="glyphicon glyphicon-log-in"></span> <?= LABEL_LOGOUT ?></a></li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
<?php //TODO: reduce code lenght ?>
<?php else: ?>
    <nav class="navbar navbar-default navbar-fixed-top" id="menu" style="background-color:#2264bb;" >

       <!-- <img class="navbar-brand" alt="MediaGrade" src="/img/logo.png" />-->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand visible-xs" href="#">MediaGrade</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li><a href="/gallery"><span class="glyphicon glyphicon-sunglasses"></span> <?=LABEL_GALLERY ?></a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="/"><span class="glyphicon glyphicon-log-in"></span> <?= LABEL_LOGIN ?></a></li>
            </ul>
        </div>
    
</nav>
<?php endif ?>
<!-- TOP MENU END -->
