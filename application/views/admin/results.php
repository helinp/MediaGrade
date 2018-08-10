<div id="content" class="col-xs-12 col-md-10 ">
	<?php $this->view('templates/submenu'); ?>
	<div class="row chapeau hidden-print">
		<div class="col-xs-6  col-md-6">
			<form  style="margin-top:1.1em!important">

				<label class="radio-inline">Vue: </label>
		    <label class="radio-inline">
		      <input type="radio" id="radio-colors" name="optradio" onclick="switchDisplayMode()" checked>Couleurs
		    </label>
		    <label class="radio-inline">
		      <input type="radio" id="radio-votes" name="optradio" onclick="switchDisplayMode()">Points
		    </label>
				<label class="radio-inline">
					<input type="radio" id="radio-both" name="optradio" onclick="switchDisplayMode()">Couleurs & Points
				</label>
		  </form>
		</div>
		<div class="col-xs-6  col-md-6">
			<div class="form-group pull-right">
				<form id="filter" action="" method="get" class="form-inline" style="margin-top:1.5em">
					<div class="pull-right">
						<label><?= _('Classe: ') ?></label>
						<select class="form-control input-sm" name="classe" onchange="this.form.submit()">
							<?php foreach($classes as $classe): ?>
								<?= '<option value="' . $classe->id . '"' . (@$_GET['classe'] === $classe->id ? 'selected' : '') . '>' . $classe->description . '</option>' . "\n" ?>
							<?php endforeach?>
						</select>
						<label><?= _('Période: ') ?></label>
						<select class="form-control input-sm" name="term" onchange="this.form.submit()">
							<option value=""><?= _('Toutes')?></option>
							<?php foreach($terms as $term): ?>
								<?= '<option value="' . $term->id . '"' . (@$_GET['term'] === $term->id ? ' selected' : '') . '>' . $term->name . '</option>' . "\n" ?>
							<?php endforeach?>
						</select>
					</form>
				</div>
			</div>
		</div>
	</div>

	<?php if(isset($table_header)):?>
		<div id="dvData" style="overflow-y: auto;margin-bottom: 1em;">
			<table class="table table-hover" id="table-gradebook" style="margin-top:5em">
				<thead>
					<tr>
						<th><span class="visible-print"><?= $this->session->last_name ?> / <?= $class->id?> / <?= ($this->input->get('term') ? $this->input->get('term') : _('Année')) ?></span> <!-- Leave for CVS export --></th>
						<?php foreach ($table_header as $row): ?>
							<?php foreach ($row['skills_groups'] as $skills_group): ?>
								<th class="rotate"><div><span><small><a data-toggle="modal" data-target="#projectModal" href="/admin/results/details/<?= $row['project_id'] ?>"  data-toggle="tooltip" data-placement="right" title="<?= _('Détails par projet')?>"><?= character_limiter($row['project_name'], 13) ?></a></small></span></div></th>

							<?php endforeach ?>
						<?php endforeach ?>
						<th class="rotate"><div><span><small><?= _('MOYENNE')?></small></span></div></th>
						<th class="rotate"><div><span><small><?= _('ÉCART')?></small></span></div></th>
					</tr>
					<tr>
						<th><small><?= _('Groupe de cpt')?></small></th>
						<?php foreach ($table_header as $row): ?>
							<?php foreach ($row['skills_groups'] as $skills_group): ?>
								<th data-toggle="tooltip" data-placement="top" title="<?= $skills_group->skills_group ?>"><?= substr($skills_group->skills_group, 0, 1)?></th>
							<?php endforeach ?>
						<?php endforeach ?>
						<th></th>
						<th></th>
					</tr>
					<tr>
						<th><small><?= _('Maximum') ?></small></th>

						<?php foreach ($table_header as $row): ?>
							<?php foreach ($row['skills_groups'] as $skills_group): ?>
								<th><small><?= $skills_group->max_vote?></small></th>
							<?php endforeach ?>
						<?php endforeach ?>

						<th><small>%</small></th><!-- Average -->
						<th><small></small></th><!-- Average -->
					</tr>
				</thead>
				<tbody>
					<td></td>
					<?php foreach ($table_body as $student): ?>
						<tr>
							<td style="min-width:10em"><?= $student['last_name'] . ' ' . $student['first_name']?></td>
							<?php foreach ($student['results'] as $projects_results): ?>
								<?php foreach ($projects_results as $result): ?>
									<td  style="width:.5em">
										<?php if(isset($result->max_vote)): ?>
											<a href="/admin/results/details/<?= $result->project_id ?>/<?= $student['user_id'] ?>" data-toggle="modal" data-target="#projectModal"><span class="gradebook-lsu"  style="background: <?=returnLSUColorFromLSUCode(convertPercentageToLSUCode(@$result->user_vote / $result->max_vote * 100)) ?>"
												data-toggle="tooltip" data-placement="top" title="<?= returnLSUTextFromLSUCode(convertPercentageToLSUCode($result->user_vote / $result->max_vote * 100)) ?>">
													&nbsp;&nbsp;&nbsp;</span></a>

											<small>	<a class="gradebook-vote" data-toggle="modal" data-target="#projectModal" <?php if (is_numeric($result->user_vote && $result->user_vote < ($result->max_vote / 2))) echo(' class="text-danger dotted_underline" ') ?> href="/admin/results/details/<?= $result->project_id ?>/<?= $student['user_id'] ?>"><?= custom_round($result->user_vote) . '/' . $result->max_vote?></small></a>
											<?php else: ?>
												<span class="gradebook-lsu" style="background: white"
													data-toggle="tooltip" data-placement="top" title="Non Evalué">&nbsp;&nbsp;&nbsp;</span>

													<a class="gradebook-vote" data-toggle="modal" data-target="#projectModal"><small>NE</small></a>
											<?php endif ?>
										</td>
									<?php endforeach ?>
								<?php endforeach ?>
								<td<?php if ($student['average'] < 50 && is_numeric($student['average'])) echo(' class="text-danger dotted_underline" ') ?>>
								<span class="gradebook-lsu" style="background: <?=returnLSUColorFromLSUCode(convertPercentageToLSUCode($student['average'])) ?>"
									data-toggle="tooltip" data-placement="top" title="<?= returnLSUMentionTextFromPercentage($student['average']) ?>"><?= $student['average']?></span></td>
									<td<?php if ($student['deviation'] < 0 && is_numeric($student['deviation'])) echo(' class="text-danger dotted_underline" ') ?>><small><?= $student['deviation']?></small></td>
								</tr>
							<?php endforeach ?>
						</tbody>
					</table>
				</div>
				<a href="#" class="btn btn-default hidden-print" id ="export" role='button'><span class="glyphicon glyphicon-export"></span> <?= _('Télécharger le CSV')?></a>
			</div>
		<?php else: ?>
			<div class = "row">
				<div class="col-md-10">
					<?= LABEL_NO_AVAILABLE_RESULTS ?>
				</div>
			</div>
		<?php endif ?>
	</div>

	<!-- Modal -->
	<div class="modal sudo"  id="projectModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content" style="padding:1em;">
			</div>
		</div>
	</div>


	<!-- Scripts ------------------------------------------------------------>
	<script type='text/javascript'>
	$(document).ready(function () {
		function exportTableToCSV($table, filename) {
			var $headers = $table.find('tr:has(th)')
			,$rows = $table.find('tr:has(td)')
			// Temporary delimiter characters unlikely to be typed by keyboard
			// This is to avoid accidentally splitting the actual contents
			,tmpColDelim = String.fromCharCode(11) // vertical tab character
			,tmpRowDelim = String.fromCharCode(0) // null character
			// actual delimiter characters for CSV format
			,colDelim = '";"'
			,rowDelim = '"\n"';
			// Grab text from table into CSV formatted string
			var csv = '';
			csv += formatRows($headers.map(grabRow));
			csv += rowDelim;
			csv += formatRows($rows.map(grabRow)) + '"';
			// Data URI
			var csvData = 'data:application/csv;charset=utf-8,' + encodeURIComponent(csv);
			$(this)
			.attr({
				'download': filename
				,'href': csvData
				//,'target' : '_blank' //if you want it to open in a new window
			});
			//------------------------------------------------------------
			// Helper Functions
			//------------------------------------------------------------
			// Format the output so it has the appropriate delimiters
			function formatRows(rows){
				return rows.get().join(tmpRowDelim)
				.split(tmpRowDelim).join(rowDelim)
				.split(tmpColDelim).join(colDelim);
			}
			// Grab and format a row from the table
			function grabRow(i,row){

				var $row = $(row);
				//for some reason $cols = $row.find('td') || $row.find('th') won't work...
				var $cols = $row.find('td');
				if(!$cols.length) $cols = $row.find('th');
				return $cols.map(grabCol)
				.get().join(tmpColDelim);
			}
			// Grab and format a column from the table
			function grabCol(j,col){
				var $col = $(col),
				$text = $col.text();
				return $text.replace('"', '""').replace(/\s+/g, ' ').trim(); // escape double quotes and remove space & carriage return
			}
		}
		// This must be a hyperlink
		$("#export").click(function (event) {
			// var outputFile = 'export'
			<?php $this->load->helper('school'); ?>
			var outputFile = '<?=  get_school_year() . '_' . $this->uri->segment(4, 0)  . '_' . $class->id ?>' + '.csv'

			// CSV
			exportTableToCSV.apply(this, [$('#dvData>table'), outputFile]);

			// IF CSV, don't do event.preventDefault() or return false
			// We actually need this to be a typical hyperlink
		});
	});
	</script>

	<!-- Updates modal-->
	<script>
	$(document).on('hidden.bs.modal', function (e) {
		$(e.target).removeData('bs.modal');
	});
	</script>

	<script>
		// display model
		$(document).ready(function () {
			$('.gradebook-lsu').show();
			$('.gradebook-vote').hide();
		});
		function switchDisplayMode() {

			if (document.getElementById("radio-votes").checked) {
				$('.gradebook-lsu').hide();
				$('.gradebook-vote').show();
			}
			else if (document.getElementById("radio-colors").checked) {
				$('.gradebook-lsu').show();
				$('.gradebook-vote').hide();
			}
			else if (document.getElementById("radio-both").checked) {
					$('.gradebook-lsu').show();
					$('.gradebook-vote').show();
				}
		}
		</script>
