<div id="content" class="col-xs-10 col-md-10 ">
	<?php $this->view('templates/submenu'); ?>
	<div class="row chapeau">
		<div class="col-xs-7 col-md-7">
		</div>
		<div class="col-md-5 ">
		</div>
	</div>

	<?php foreach($results_by_skill as $result): ?>
		<?php if($result['results'] !== '--'):?>

				<div class="row results-overview-row">
					<div class="col-md-4" style="border-left:none">
						<h5>Compétence</h5>
						<?= $result['skill']->skill_id; ?> - <?= $result['skill']->skill ?>
					</div>
						<div class="col-md-4">
							<h5>Evaluation</h5>
						<big  class="overall-results" style="font-size:1.5em;background-color:<?=returnLSUColorFromPercentage($result['result']) ?>">
							<?= returnFunMentionTextFromPercentage($result['result'])?>
						</big>
						<p><?= returnTextExplainationsFromPercentage($result['result']) ?></p>
					</div>
					<div class="col-md-4">
						<h5>Critères d'évaluation</h5>
						<table class="table table-condensed">
							<?php foreach ($result['results'] as $result): ?>
								<tr>
									<td>
										<td><span class="lsu lsu-<?= convertPercentageToLSUCode($result->percentage) ?>" data-toggle="tooltip" data-placement="top" title="<?= returnLSUTextFromLSUCode(convertPercentageToLSUCode($result->percentage)) ?>">&nbsp;&nbsp;&nbsp;&nbsp;</span>
										</td>
										<td>
											<p><?= $result->criterion ?> (<?= $result->n_assessments?>)</p>
										</td>
									</tr>
								<?php endforeach ?>
							</table>
						</div>
					</div>

			<?php endif ?>
		<?php endforeach ?>
	</div>
