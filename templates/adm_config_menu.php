    <aside id="projects" class="col-md-2 bs-docs-sidebar">
        <nav itemscope itemtype="http://schema.org/SiteNavigationElement">
            <ul class="list-group small" itemprop="project">
                    <li><a class="list-group-item<?= (isset($_GET['skills']) ?  ' active' : '') ?>" href="config.php?skills"><?= LABEL_SKILLS ?><span class="glyphicon glyphicon-pencil pull-right"></a></li>
                    <li><a class="list-group-item<?= (isset($_GET['users']) ?  ' active' : '') ?>" href="config.php?users"><?= LABEL_CLASS_ROLL ?><span class="glyphicon glyphicon-pencil pull-right"></a></li>
                    <li><a class="list-group-item<?= (isset($_GET['welcome']) ?  ' active' : '') ?>" href="config.php?welcome"><?= LABEL_CONFIG_WELCOME ?><span class="glyphicon glyphicon-pencil pull-right"></a></li>
                    <li><a class="list-group-item<?= (isset($_GET['system']) ?  ' active' : '') ?>" href="config.php?system"><?= LABEL_SYSTEM ?><span class="glyphicon glyphicon-pencil pull-right"></a></li>
            </ul>
        </nav>
    </aside>
