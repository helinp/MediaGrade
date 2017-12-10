

<div id="content" class="col-xs-12 col-md-10 ">
  <div class="row chapeau hidden-print">
    <div class="col-xs-6  col-md-6">
      <h1><?= _("Exporter les leçons")?></h1>
    </div>
    <div class="col-xs-6  col-md-6">
      <form id="filter" action="" method="get" class="form-inline" style="margin-top:1.5em">
        <label><?= _('Classe: ') ?></label>
          <select class="form-control input-sm" name="class" onchange="this.form.submit()">
            <option value=""><?= _('Toutes')?></option>
            <?php foreach($classes as $class): ?>
              <?= '<option value="' . $class . '"' . (@$_GET['class'] === $class ? 'selected' : '') . '>' . $class . '</option>' . "\n" ?>
            <?php endforeach?>
          </select>
		  <label><?= _('Année scolaire') ?>: </label>
            <select class="form-control input-sm" name="school_year" onchange="this.form.submit()">
              <?php foreach($school_years as $school_year): ?>
                <?= '<option value="' . $school_year->school_year . '"' . (@$_GET['school_year'] === $school_year->school_year ? 'selected' : '') . '>' . $school_year->school_year . '</option>' . "\n" ?>
			<?php endforeach?>
            </select>
      </form>
    </div>
  </div>


  <!-- BY CLASS -->
  	<h3><?= _('Projets') ?></h3>
	<div class="row">
		<form method="POST" action="/admin/export/pdf_lesson">
			<div class="col-md-12">
					<div class="form-group">


					    <select class="form-control" name="projects[]" size="10" multiple>
						<?php foreach($projects as $project): ?>
			         		<option value="<?= $project->project_id?>"><?= $project->term . ' / ' .  $project->project_name?></option>
					 	<?php endforeach ?>
						</select>
					</div>

			</div>
		</div>
		<div class="row">
			<div class="col-md-3">
				<input type="submit" class="btn btn-primary" value="<?= _('Télécharger les leçons')?>">
			</div>
		</div>
	</form>
</div>