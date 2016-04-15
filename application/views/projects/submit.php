
<main class="col-md-9 col-lg-10" id="content">
<h2><?= $project_name ?><small> / <?= LABEL_SUBMIT?></small></h2>
<hr style="margin-top:0;" />
<form action="/projects/upload/<?= $project_id ?>" role="form" enctype="multipart/form-data" method="post" data-toggle="validator">
    <div class="form-group">
        <h4><span class="glyphicon glyphicon-download-alt"></span> <?= ($submit->number_of_files > 1 ? LABEL_SUBMIT_FILES : LABEL_SUBMIT_FILE) ?></h4>
        <?php $number_of_files = $submit->number_of_files;
              while($number_of_files--): ?>
        <?php if (!empty($submit->extension)):?>
      <label for="inputfile">
            <?=LABEL_SELECT_FILE ?>
        </label>
        <input type="hidden" name="MAX_FILE_SIZE" value="<?= MAX_UPLOAD_FILE_SIZE ?>" />
        <input id="inputfile" name="submitted_file_<?= $number_of_files ?>" data-error="<?= LABEL_NO_FILE ?>" type="file" required>



        <p class="help-block with-errors">Max.
            <?=format_bytes(MAX_UPLOAD_FILE_SIZE) ?>.</p>

        <?=( empty($submitted[$number_of_files]->file) ? '' : '<p>' . LABEL_SUBMITTED_FILE . '
          <a href="' . $submitted[$number_of_files]->file . '">'
          . $submitted[$number_of_files]->file . '</a></p>') ?>
            <?php endif ?>
            <hr />
            <?php endwhile ?>

    </div>

    <?php if( ! empty($self_assessment)): ?>

    <h4><span class="glyphicon glyphicon-pencil"></span><?= LABEL_SELF_ASSESSMENT ?></h4>

    <div class="well">
        <?php $i = 0; ?>
        <?php foreach($self_assessment as $question): ?>
        <div class="form-group">
            <label>
                <?=$question['question'] ?><input type="hidden" name="self_assessment_id[]" value="<?=$question['id'] ?>" />
            </label>
            <textarea name="self_assessment[] ?>" class="form-control" rows="3" data-error="<?= LABEL_REQUIRED_ANSWER ?>" required><?php if(isset($question['answer'])) echo($question['answer']); ?></textarea>
            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
            <span class="help-block with-errors"></span>
        </div>
        <?php $i++; ?>
        <?php endforeach ?>
    </div>
    <?php endif ?>
    <button type="submit" class="btn btn-primary">
        <?=LABEL_SUBMIT_WORK ?>
    </button>

</form>

</main>
