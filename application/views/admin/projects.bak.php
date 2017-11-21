<div id="content" class="col-xs-12 col-md-10 ">

    <div class="row chapeau">
        <div class="col-xs-9  col-md-9">
            <h1> <?= _('Gestion des projets') ?>
                <a data-toggle="modal" data-target="#projectModal" href="/admin/project_management/new" class="btn btn-xs btn-primary"  role="button" style="font-style:normal"> <span class="glyphicon glyphicon-plus"></span> Nouveau</a>
            </h1>
        </div>
        <div class="col-xs-3  col-md-3">
            <form id="filter" action="" method="get" class="form-inline" style="margin-top:1.5em">
                <label><?= _('Année scolaire') ?>: </label>
                <div class="input-group">
                    <select class="form-control input-sm" name="school_year" onchange="this.form.submit()">
                        <?php foreach($school_years as $school_year): ?>
                            <?= '<option value="' . $school_year->school_year . '"' . (@$_GET['school_year'] === $school_year->school_year ? 'selected' : '') . '>' . $school_year->school_year . '</option>' . "\n" ?>
                        <?php endforeach?>
                    </select>
                </div>
            </form>
        </div>
    </div>
    <?php $term_tmp = NULL; $curr = 0; ?>

    <?php foreach ($projects as $project):?>
        <?php
        if ($term_tmp !== $project->term)
        {
            $term_tmp = $project->term;
            echo '<h3>' ._('Période') . ' ' . $term_tmp . '</h3>
            <div class="row">';
                $classe_tmp = $project->class;
            }
            if ($classe_tmp !== $project->class)
            {
                echo '<div class="row">';
                $classe_tmp = $project->class;
            }
            ?>


            <div class="col-xs-12 col-md-3 col-lg-3">
                <div class="panel panel-default <?php

                if($project->is_activated && countdown($project->deadline)) echo 'current';
                elseif ($project->is_activated && !countdown($project->deadline)) echo 'reached-deadline';
                else echo 'desactivated';

                ?>">
                <div class="panel-body" style="min-height: 9em">
                    <p><b><?= $project->project_name; ?> </b><br /><?= $project->class . ' - ' . $project->term; ?></p>
                    <p><span class="glyphicon glyphicon-time"> </span> <?= date_format(date_create($project->deadline),"d/m/Y"); ?><br /><a data-toggle="modal" data-target="#projectModal"  href="/admin/instructions/<?= $project->project_id ?>"><span class="glyphicon glyphicon-file"></span> Consignes</a>
                    </p>
                </div>
                <div class="panel-footer clearfix">
                    <?php if (countdown($project->deadline)): ?>
                        <span class="label label-info">J - <?= countdown($project->deadline); ?></span>
                    <?php endif ?>
                    <span class="label label-info"><?php

                        if($project->is_activated && countdown($project->deadline)) echo _('En cours');
                        elseif ($project->is_activated && !countdown($project->deadline)) echo _('Terminé');
                        elseif (!$project->is_activated) echo _('Désactivé');

                        ?></span>
                        <div class="dropdown pull-right">
                          <a data-toggle="modal" data-target="#projectModal" href="/admin/project_management/<?= $project->project_id; ?>" class="btn btn-default btn-xs" role="button">
                            <?=_('Modifier') ?></a>
                        </div>
                    </div>
                </div>
            </div>



            <?php

            if ($projects[$curr]->class !== @$projects[$curr + 1]->class && $projects[$curr]->term == @$projects[$curr + 1]->term)
            {
                echo '<hr style="border-top:dashed 1px #ddd ;width:100%"/></div>';
            }
            elseif ($projects[$curr]->class !== @$projects[$curr + 1]->class)
            {
                echo '</div>';
            }
            elseif ($projects[$curr]->term !== @$projects[$curr + 1]->term)
            {
                echo '</div> <!-- row -->';
            }
            $curr++;
            ?>
        <?php endforeach ?>


    </div>

    <!-- Modal -->
    <div class="modal sudo"  id="projectModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="padding:1em;">
            </div>
        </div>
    </div>

    <!-- Updates modal-->
    <script>
        $(document).on('hidden.bs.modal', function (e) {
            $(e.target).removeData('bs.modal');
        });
    </script>
