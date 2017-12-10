<div id="content" class="col-xs-12 col-md-10 ">
  <div class="row chapeau hidden-print">
    <div class="col-xs-6  col-md-6">
      <h1><?= _("Générer les fiches d'évaluation")?></h1>
    </div>
    <div class="col-xs-6  col-md-6">
      <form id="filter" action="" method="get" class="form-inline" style="margin-top:1.5em">
        <label><?= _('Période: ') ?></label>
          <select class="form-control input-sm" name="term" onchange="this.form.submit()">
            <option value=""><?= _('Toutes')?></option>
            <?php foreach($terms as $term): ?>
              <?= '<option value="' . $term . '"' . (@$_GET['term'] === $term ? 'selected' : '') . '>' . $term . '</option>' . "\n" ?>
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


  <!-- BY PROJECT -->
  <form method="POST" action="/admin/export/pdf_project">
   <div class="form-group">
     <h3><?= _('Projets') ?></h3>
     <select class="form-control" name="project_id[]" size="15" multiple>
       <?php foreach($projects as $project): ?>
         <option value="<?= $project->project_id?>"><?= $project->term . ' - ' . $project->class . ' / ' . $project->project_name?></option>
       <?php endforeach ?>
     </select>
   </div>

   <div class="form-group">
     <input type="submit" class="btn btn-primary" value="Générer PDF" name="action">
   </div>
 </form>

</div>
