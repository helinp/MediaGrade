        <main class="col-md-9  col-lg-10" id="content">
          <h2><?= $project_name ?><small> / <?= LABEL_INSTRUCTIONS?></small></h2>
          <hr style="margin-top:0;" />

          <?= ($content ? $content : LABEL_NO_INSTRUCTIONS)?>

        </main>
