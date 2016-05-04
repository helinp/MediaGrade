<main class="col-md-8 col-lg-9" id="content">
    <h2><?= $project_name ?><small> / <?= LABEL_RESULTS?></small></h2>
    <hr style="margin-top:0;" />
    <div class="row">
        <?php if(!empty($submitted)): ?>
            <?php   $n = count($submitted);
                if($n == 1) {$max_files = 1 ; $cols = 4;}
                elseif($n < 7) { $max_files = 6 ; $cols = 2;}
                else { $max_files = 12 ; $cols = 2;}
                ?>
            <?php foreach ($submitted as $project_url): ?>
                <div class="col-md-<?= $cols ?>">
                    <?php if ($project_url->extension == "mp4" || $project_url->extension == "mov" || $project_url->extension == "avi"):?>
                          <div class="thumbnail">
                              <div class="embed-responsive embed-responsive-16by9">
                                  <video class="embed-responsive-item" preload="metadata" controls>
                                      <source src="<?= $project_url->file?>" type="video/mp4">
                                      <p><?= LABEL_NO_HTML5_VIDEO ?> <a href="<?= $project_url->file ?>"><?= LABEL_HERE ?></a></p>
                                  </video>
                              </div>
                          </div>


                <?php elseif($project_url->extension == "jpeg" ||$project_url->extension == "jpg" || $project_url->extension == "png" || $project_url->extension == "gif"): ?>
                      <div class="thumbnail">
                          <a  href="<?= $project_url->file ?>">
                          <img class="img-responsive thumbnail-180" src="<?= $project_url->thumbnail ?>" alt="<?= $project_url->file ?>" />
                          </a>
                      </div>
                <?php endif ?>
                </div>
            <?php endforeach ?>
        <?php endif ?>
    </div>
    <?php if (!empty($comments)):?>
    <?= '<hr /><h3>' . LABEL_COMMENT . '</h3><pre class="comment">' . $comments . '</pre><hr />'?>
    <?php endif ?>

    <h3><?= LABEL_RESULTS ?></h3>
    <?php if (!empty($results)):?>
    <table class="table table-hover ">
        <thead>
            <tr>
                <th><?= LABEL_SKILLS ?></th>
                <th><?= LABEL_CRITERION ?></th>
                <th><?= LABEL_CURSOR ?></th>
                <th><?= LABEL_RESULTS ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($results as $result)
                {
                    //dump($result);
                    $percentage = 0;
                    if ($result->user_vote !== 0) $percentage = ($result->user_vote / $result->max_vote) * 100;

                    $class = "";

                    if ($percentage > 79) $class = "success";
                    if ($percentage < 49) $class = "warning";
                    if ($percentage < 30) $class = "danger";


                    echo
                    (
                    '<tr class=' .'"' . $class . '">'
                            . '<td>' . $result->skills_group . "</td>\n"
                            . '<td>' . $result->criterion . "</td>\n"
                            . '<td>' . $result->cursor . "</td>\n"
                            . '<td>' . $result->user_vote . " / " . $result->max_vote . "</td>\n"
                    . "</tr>"
                    );
                }
                ?>
        </tbody>
    </table>
    <?php else: ?>
    <?= LABEL_NOT_GRADED_YET ?>
    <?php endif ?>
</main>
