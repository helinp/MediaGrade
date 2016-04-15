
<main class="col-md-10">
    <h2><?= _("Générer les fiches d'évaluation")?></h2>

        <hr style="margin-top:0;" />

            <!---------- BY PROJECT ------------->
            <form method="POST" action="/pdf_project">
                 <div class="form-group">
                     <h3><?= _('Projets') ?></h3>
                     <select class="form-control" name="project_id[]" size="10" multiple>
                     <?php foreach($projects as $project): ?>
                         <option value="<?= $project->project_id?>"><?= $project->class . ' / ' . $project->project_name?></option>
                     <?php endforeach ?>
                     </select>
                 </div>

                 <div class="form-group">
                     <input type="submit" class="btn btn-primary" value="Générer PDF" name="action">
                 </div>
            </form>
</main>
