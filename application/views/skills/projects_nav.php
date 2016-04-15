<aside id="projects" class="col-sm-3 col-md-2 sidebar-wrapper p_sidemenu">
    <nav>
        <ul class="list-group small">
            <li><a class="list-group-item alert-info<?php
                if(!isset($curr_project->project_id))
                {
                    echo(" active");
                };

                ?>" href="projects.php"><?= LABEL_NEW_PROJECT ?><span class="glyphicon glyphicon-pencil pull-right"></span></a></li>
            <?php foreach ($projects as $project):?>
            <li><a class="list-group-item<?php

                    if($this->uri->segment(3, 0) == $project->project_id)
                    {
                        echo(" active");
                    }


                ?>" href="/admin/projects/<?= $project->project_id; ?>">
                    <?= ($project->is_activated ? '' : '<span class="glyphicon glyphicon-ban-circle" style="color:#d9534f"></span> ')?>
                    <b><?= $project->class . " / " . $project->project_name . "</b><br /><em>P" . $project->periode . " / " . $project->deadline ?></em>
                    <span class="glyphicon glyphicon-chevron-right pull-right"></span></a></li>
            <?php endforeach?>
        </ul>
    </nav>
</aside>
