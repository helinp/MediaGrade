<div id="content" class="col-xs-12 col-md-10 ">
	<?php $this->view('templates/submenu'); ?>
    <div class="row chapeau">
    </div>
    <div class="alert alert-danger" style="margin-top:1em;" role="alert"><?= LABEL_ADMIN_DANGER ?></div>

		<h3><?= _('Distribuer les badges')?></h3>
		<p><?= _('Les badges sont attribués aux élèves ayant obtenu en moyenne <b>plus de 79%</b> à au moins 3 de ses dernières évaluations formatives ou à sa dernière évaluation certificative.') ?></p>
		<form action="/admin/achievements/reward" method="post">
			<button type="submit" class="btn btn-primary"><?= _('Attribuer automatiquement') ?></button>
		</form>

        <h3><?= _('Ajouter un badge')?></h3>
           <form action="/admin/achievements/add" method="post">
               <table id="rows" class="table">
				   <col width="5%">
				   <col width="25%">
				   <col width="5%">
				   <col width="40%">
				   <col width="15%">
				   <col width="5%">
				   <col width="5%">
     	            <thead>
	                    <tr>
							<th></th>
		                    <th><?= _('Nom') ?></th>
							<th><?= _('Étoiles') ?></th>
		                    <th><?= _('Description') ?></th>
                            <th><?= _('URL icône') ?></th>
		                    <th></th>
		                    <th></th>
	                    </tr>
	                </thead>
	                <tbody>
                        <tr>
							<td><img id="live-url" alt="Preview"  style="width:3em" /></td>
                            <td><input name="name" class="form-control input-sm" required></td>
							<td><input name="star" class="form-control input-sm" required></td>
                            <td><textarea name="description" class="form-control input-sm" required></textarea></td>
                            <td><input name="icon" class="form-control input-sm" id="live-url-input" required></td>
                            <td><button type="submit" name="add" class="btn btn-primary btn-xs" value="add"><span class="glyphicon glyphicon-plus"></span></button></td>
                        </tr>
                    </tbody>
                </table>
           </form>
           <h3><?= _('Liste des badges')?></h3>
               <table class="table table-striped">
				   <col width="5%">
				   <col width="25%">
				   <col width="5%">
				   <col width="40%">
				   <col width="15%">
				   <col width="5%">
				   <col width="5%">

                    <tbody>
                <?php foreach($achievements as $achievement): ?>

                        <tr>
                            <form action="/admin/achievements/update" method="post" style="display:inline;">
								<td><img src="<?= $achievement->icon ?>" style="width:3em" /></td>
                                <td><input name="name" class="form-control input-sm" value= "<?= $achievement->name ?>" required></td>
								<td><input name="star" class="form-control input-sm" value= "<?= $achievement->star ?>" required></td>
                                <td><textarea name="description" class="form-control input-sm" required><?= $achievement->description ?></textarea></td>
                                <td><input name="icon" class="form-control input-sm" value= "<?= $achievement->icon ?>" required></td>
                                <td><button type="submit" class="btn btn-primary btn-xs" name="id" value="<?=  $achievement->id?>"><span class="glyphicon glyphicon-pencil"></span></button></td>
                            </form>
                            <form action="/admin/achievements/delete" method="post" style="display:inline;">
                                <td><button type="submit" class="btn btn-danger btn-xs" name="id" value="<?= $achievement->id ?>"><span class="glyphicon glyphicon-trash"></span></button></td>
                            </form>
                        </tr>

               <?php endforeach?>
                    </tbody>
               </table>
</div>

<script>
$('#live-url-input').keyup(function() {
    var url = $('#live-url-input').val();
	 $('#live-url').attr("src", url);
});
</script>
