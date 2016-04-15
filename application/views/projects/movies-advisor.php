        <main class="col-sm-8 col-md-9" id="content">
          <h2><?= $project_name ?><small> / <?= LABEL_INSTRUCTIONS?></small></h2>
          <hr style="margin-top:0;" />

          <?= ($content ? $content : LABEL_NO_INSTRUCTIONS)?>

        </main>
