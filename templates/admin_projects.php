
 <div class="row">
    <aside id="projects" class="col-md-2 bs-docs-sidebar">
        <nav itemscope itemtype="http://schema.org/SiteNavigationElement">
            <ul class="list-group small" itemprop="project">
                    <li><a class="list-group-item alert-info"href="admin.php">Nouveau projet<span class="glyphicon glyphicon-pencil pull-right"></a></li>
    <?php foreach ($projects as $project):?>
                    <li><a class="list-group-item" href="admin.php?project=<?= $project["project_id"] ?>"><?= $project["project_name"]?><span class="glyphicon glyphicon-chevron-right pull-right"></a></li>
    <?php endforeach?>
            </ul>
        </nav>
    </aside>
  



