<div id="content" class="col-xs-10 col-md-10 ">
	<div class="row chapeau">
		<div class="col-xs-9 col-md-9">
		</div>
		<div class="col-xs-3  col-md-3">
		</div>
	</div>

	<!-- ACHIEVEMENTS -->
	<div class="row" style="margin-top:1em">
		<div class="col-md-12">
			<h3><?=_('Obtenus')?></h3>
			<?php if ( ! $achievements): ?>
				<?= _('Aucun badge pour le moment, mais cela ne saurait tarder!') ?></div>
			<?php endif ?>
			<?php foreach($achievements as $row): ?>
				<div class="col-lg-3 col-md-3 col-xs-12 ">
					<div class="panel panel-default achievement-container">
						<div class="panel-body text-center">
							<div class="achievement-frame ">
								<img src='<?= $row->icon ?>' alt='<?= $row->name ?>' class="achievement-img "/>
							</div>
							<h4><b><?= $row->name ?></b></h4>
							<?= str_repeat('<img src="/assets/img/badges/star.svg" class="achievement-star-img" alt="stars" />', $row->star) ?>

							<p>"<?= $row->description; ?>"</p>
						</div>
					</div>
				</div>
			<?php endforeach ?>
		</div>
	</div>
	<!-- ALL ACHIEVEMENTS -->
	<div class="row" style="margin-top:1em">
		<div class="col-md-12">
			<h3><?=_('Encore à obtenir')?></h3>
		</div>
	</div>
	<div class="row" style="margin-top:1em">

		<?php foreach($all_unrewarded_achievements as $row): ?>
			<div class="col-lg-3 col-md-3 col-xs-12 ">
				<div class="panel panel-default achievement-container inactive">
					<div class="panel-body text-center">
						<div class="achievement-frame ">
							<img src='<?= $row->icon ?>' alt='<?= $row->name ?>' class="achievement-img "/>
						</div>
						<h4><b><?= $row->name ?></b></h4>
						<?= str_repeat('<img src="/assets/img/badges/star.svg" class="achievement-star-img" alt="stars" />', $row->star) ?>

						<p>"<?= $row->description; ?>"</p>
					</div>
				</div>
			</div>
		<?php endforeach ?>
	</div>
</div>

<!-- Modal -->
<div class="modal sudo"  id="projectModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content" style="padding:1em;">
		</div>
	</div>
</div>

<!-- Updates modal-->
<script>
$(document).on('hidden.bs.modal', function (e) {
	$(e.target).removeData('bs.modal');
});
</script>

<!-- Tool tips-->
<script type="text/javascript">
$(document).ready(function(){
	$('[data-toggle="tooltip"]').tooltip();
});
</script>
