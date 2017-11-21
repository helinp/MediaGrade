<!DOCTYPE html>
<html lang="<?= LANG ?>">
	<head>
        <meta charset="UTF-8">

        <link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.min.css" />
        <link rel="stylesheet" type='text/css' href="/assets/css/lightbox.css" />
        <link rel="stylesheet" type="text/css" href="/assets/css/datepicker.min.css" />
        <link rel="stylesheet" type="text/css" href="/assets/css/datepicker3.min.css" />
        <link rel="stylesheet" type='text/css' href="/assets/css/styles.css"/>
		<link href="/assets/css/dropzone.css" type="text/css" rel="stylesheet" />
		<script src="/assets/js/jquery-3.1.1.min.js"></script>
		    </head>

		    <body>

		<script src="/assets/js/dropzone.js"></script>
		<div class="row chapeau chapeau-modal">
				<div class="col-xs-12  col-md-12">
						<h2 class=" text-left"><?= _('Remise d\'un projet')?><small> / <?=$project->project_name?></small><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></h2>

				</div>
		</div>

		<script>
		Dropzone.autoDiscover = false;

		var myDropzone = new Dropzone("div#my-awesome-dropzone", {
		  	url: "/projects/upload/<?= $project->id ?>",
			addRemoveLinks: true,
			autoProcessQueue: false,
			maxFilesize: <?= (int) (MAX_UPLOAD_FILE_SIZE / 1000 / 1000) ?>, // MB
			uploadMultiple: true,
			acceptedFiles: '<?= implode(',', $project->mime)?>', // MIME
			maxFiles: <?= $submit->number_of_files ?>,
			maxFiles: <?= $submit->number_of_files ?>,
			dictRemoveFile: 'Retirer',
			dictDefaultMessage: 'Cliquez ou Glissez vos fichiers ici',
			dictMaxFilesExceeded: 'Il semble qu\'il y ait trop de fichiers...',
			dictInvalidFileType: 'Vérifiez le format de votre fichier',
			dictFileTooBig: 'Votre fichier est trop lourd',
			init: function() {
		    var myDropzone = this,
		    	submitButton = document.querySelector("#upload-button");

		    submitButton.addEventListener('click', function(e) {
		        e.preventDefault();
		        e.stopPropagation();
		        myDropzone.processQueue();
		    });
		    myDropzone.on('sending', function(data, xhr, formData) {
				$("form").find("input").each(function(){
			        formData.append($(this).attr("name"), $(this).val());
					document.querySelector("form").style.display = "none";
					document.querySelector("#progress-div").style.display = "block";
					$("#message-progress").text("Téléversement en cours...");
					//console.log($(this).val());
			    });
				$("form").find("textarea").each(function(){
			        formData.append($(this).attr("name"), $(this).val());
					//console.log($(this).val());
			    });
		    });
		    myDropzone.on('success', function(files, response) {
				//var r = confirm(response);
				document.querySelector("#total-progress .progress-bar").style.width = "100%";
				$("#message-progress").text(response);
				document.querySelector("#close-button").style.display = "block";
				//if(r){window.location.replace('/projects/overview')};
		    });
		    myDropzone.on('error', function(files, response) {
				document.querySelector("#total-progress").style.display = "none";
				document.querySelector("span#failed").style.display = "block";
				document.querySelector("#close-button").style.display = "block";
		        $("#message-progress").html(response);
		    });

			// Update the total progress bar
				myDropzone.on("totaluploadprogress", function(progress) {
			 	document.querySelector("#total-progress .progress-bar").style.width = progress - 5 + "%";
			});

			myDropzone.on("queuecomplete", function(progress) {
			  // document.querySelector("#total-progress").style.opacity = "0";
			});
		  }
		});

		$('#close-button').on('click', function () {
		 location.reload();
		})
		</script>


		<form role="form" action="/projects/upload/<?= $project->id ?>" enctype="multipart/form-data" method="POST" data-toggle="validator">

			<h4><span class="glyphicon glyphicon-save-file"></span> <?= _('Fichiers') ?></h4>
			<p><?= _('Extension demandée') . ': <em style="font-weight:600">.' . $project->extension . '</em>
			<br /> ' . _('Nombre à remettre:') . ' <em style="font-weight:600">' . $submit->number_of_files . '</em><br />'.
			_('Taille maximale:') . ' <em style="font-weight:600">' . format_bytes(MAX_UPLOAD_FILE_SIZE) . '</em></p>' ?>

			<div class="dropzone" id="my-awesome-dropzone"></div>

			<div class="form-group">
		        <?php $number_of_files = $submit->number_of_files;?>

		        <p class="help-block with-errors"></p>

				<?php if(empty($submitted[$number_of_files]->file)): ?>

				<?php else: ?>
		        <h5> <?= _('Fichier remis') ?> </h5>
		        <a target="_new" style="display:block;" href="<?= $submitted[$number_of_files]->file ?>">
		            <img src="<?= $submitted[$number_of_files]->thumbnail ?>" alt="thumbnail" style="height:10em;" />
		        </a>
		        <hr />
		        <?php endif ?>
		    </div>

		    <?php if( ! empty($self_assessment)): ?>

		    <h4><span class="glyphicon glyphicon-pencil"></span> <?= LABEL_SELF_ASSESSMENT ?></h4>
		    <div class="well">
		        <?php $i = 0; ?>
		        <?php foreach($self_assessment as $question): ?>
		        <div class="form-group">
		            <span class="submit-self-assessment">
		                <?=$question['question'] ?><input type="hidden" name="self_assessment_id[]" value="<?=$question['id'] ?>" />
		            </span>
		            <textarea name="self_assessment[] ?>" class="form-control" rows="3" data-error="<?= LABEL_REQUIRED_ANSWER ?>" required><?php if(isset($question['answer'])) echo str_replace('&lt;br/&gt;', "\r", $question['answer']); ?></textarea>
		            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
		            <span class="help-block with-errors"></span>
		        </div>
		        <?php $i++; ?>
		        <?php endforeach ?>
		    </div>
		    <?php endif ?>

		    <button type="button" class="btn btn-primary" id="upload-button">
		        <?=LABEL_SUBMIT_WORK ?>
		    </button>

		</form>
		<big><span id="failed" style="display:none;text-align:center;font-size: 5em;" class="glyphicon glyphicon-ban-circle text-danger text-center" ></span></big>
		<div id="progress-div" style="display:none;text-center;">
			<div id="total-progress" class="progress progress active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
				<div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress></div>
			</div>
			<p id="message-progress" style="text-align:center"></p>
			<div class="row" style="margin-top:1em">
				<div class="col-md-2 col-md-offset-5 text-center">
					<button id="close-button" style="display:none" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">Fermer</span></button>
				</div>
			</div>

		</div>
	</body>
</html>
