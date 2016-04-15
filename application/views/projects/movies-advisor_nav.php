            <aside id="projects" class="col-sm-3 col-md-2 sidebar-wrapper p_sidemenu">
                    <?php if( ! empty($_SESSION['avatar'])):?>
                <div style="margin: 0 auto;">
                    <img src="<?= $_SESSION['avatar']?>" alt="user_avatar" class=" center-block img-circle img-responsive" style="text-align:center;width:100px" />
                </div>
                    <?php endif ?>
                <div style="margin-top:1em;"></div>
                <?php if(!isset($projects)) $projects = array() ?>
                <?php foreach ($projects as $project):?>
                <nav>
                    <ul class="list-group" >
                        <li class="list-group-item" style="margin:0 0 0 0">
                            <div class="periode">
                                <span class="btn  btn-circle" ><?= 'P' . $project->periode ?></span>
                            </div>
                            <div style="float:left;margin-left:1em;">
                                <h5 class="active list-group-item-heading active"> <?= character_limiter($project->project_name, 10) ?></h5>
                                <p class="list-group-item-text" style="color:gray;margin-top:0"><em><small>
                                  <?php if($project->is_submitted):?>
                                    <span class="glyphicon glyphicon-ok text-success"></span> <?=LABEL_SUBMITTED_PROJECT?>
                                  <?php else: ?>
                                    <span class="glyphicon glyphicon-flag text-danger" aria-hidden="true"></span> <?= $project->deadline ?>
                                  <?php endif ?>
                                </small></em></p>


                            </div>
                            <div style="clear: both;"></div>
                        </li>
                        <li class="list-group-item"  style="margin:0;padding:0;border-top:1px solid #0B0B3B;">
                            <div class="btn-group  btn-group-justified"  role="group" aria-label="...">
                                <a href="<?= base_url() . 'projects/instructions/' . $project->project_id ?>" class="btn btn-md" role="button" style="border:none"><span class="glyphicon glyphicon-file" aria-hidden="true"></span></a>
                                <a href="<?= base_url() . 'projects/submit/' . $project->project_id ?>" class="btn btn-md" role="button" style="border-left:1px solid #0B0B3B"><span class="glyphicon glyphicon-import" aria-hidden="true"></span></a>
                                <a href="<?= base_url() . 'projects/results/' . $project->project_id ?>" class="btn btn-md" role="button" style="border-left:1px solid #0B0B3B"><span class="glyphicon glyphicon-stats  text-muted" aria-hidden="true"></span></a>
                            </div>
                        </li>
                    </ul>
                </nav>
                <?php endforeach?>
            </aside>
