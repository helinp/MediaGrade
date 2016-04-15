<aside id="projects" class="col-sm-3 col-md-2 sidebar-wrapper p_sidemenu">
    <nav itemscope itemtype="http://schema.org/SiteNavigationElement">
        <h4><?= $project->project_name?></h4>
        <ul class="list-group small" itemprop="project">
            <?php foreach($grade_table{$this->uri->segment(3, 0)} as $users_projects): ?>

                <li>
                    <a class="list-group-item <?= ($users_projects['user']->id == $this->uri->segment(5, 0)) ? "active" : ""?>"
                        href="<?= '/admin/grade/' . $this->uri->segment(3, 0) . '/' . $this->uri->segment(4, 0) . '/' . $users_projects['user']->id ?>">
                    <?= strtoupper($users_projects['user']->last_name) . " " . $users_projects['user']->name ?>

                    <?php if(FALSE): ?>
                    <span class="pull-right glyphicon glyphicon-check text-success"</span>
                    <?php else: ?>
                    <span class="pull-right glyphicon"></span>
                    <?php endif ?>
                    <!--   : "glyphicon glyphicon-pencil\"") : "\"") ?>> -->
                    </a>
                </li>
            <?php endforeach ?>
        </ul>
    </nav>
</aside>
