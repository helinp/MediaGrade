<?php if( ! $this->input->get('modal')): ?>
<div id="content" class="col-xs-12 col-md-10 ">
    <div class="row chapeau">
        <div class="col-xs-6  col-md-6">
            <h1> <?= _('Statistiques par projet') ?></h1>
        </div>
        <div class="col-xs-6  col-md-6">
            <form id="filter" action="" method="get" class="form-inline" style="margin-top:1.5em">
                <label><?= _('Classe: ') ?></label>
                <select class="form-control input-sm" name="classe" onchange="this.form.submit()">
                    <option value=""><?= _('Toutes')?></option>
                    <?php foreach($classes as $classe): ?>
                        <?= '<option value="' . $classe . '"' . (@$_GET['classe'] === $classe ? 'selected' : '') . '>' . $classe . '</option>' . "\n" ?>
                    <?php endforeach?>
                </select>
				<label><?= _('Projet: ') ?></label>
                <select class="form-control input-sm" name="project" onchange="this.form.submit()">
                    <option value=""><?= _('Merci de choisir un projet')?></option>
                    <?php foreach($projects as $project): ?>
                        <?= '<option value="' . $project->project_id . '"' . (@$_GET['project'] === $project->project_id ? ' selected' : '') . '>' . $project->class . ' | ' . $project->project_name . '</option>' . "\n" ?>
                    <?php endforeach?>
                </select>
            </form>
        </div>
    </div>
<?php endif ?>
    <div class="row" style = "margin-top:1em">
        <div class="col-lg-4 col-md-4 col-xs-12 ">
        </div>
    </div>
	<script src="../assets/js/highcharts.js"></script>
    <!-- row -->
    <div class="row">
		<?php if($students_results): ?>
		<!-- Nombre de remises -->
        <div class="col-lg-4 col-md-4 col-xs-12 ">
            <div class="panel panel-primary">
                <div class="panel-heading text-center"><?= _('Évaluations')?>  </div>
                <div class="panel-body text-left">
					<div id="correction-chart" style="margin: 0 auto"></div>
					<script>

					// Create the chart
					Highcharts.chart('correction-chart', {
						chart: {
							type: 'pie'
						},
						title: {
							text: ''
						},
						subtitle: {
							text: ''
						},
						yAxis: {
							title: {
								text: ''
							}
						},
						plotOptions: {
							pie: {

								showInLegend: true,
								dataLabels: {
									enabled: false
								},
							}
						},
						tooltip: {
							valueSuffix: ' copie(s)'
						},
						series: [{
							name: 'Corrigés',
							data: [['Corrigés', <?= $n_submitted - $n_to_assess ?>], ['Non corrigés', <?= $n_to_assess?>]],
							colors: ['purple', '#9E7BFF'],
							size: '100%',
							innerSize: '90%',
							borderColor: 'white'
						}, {
							name: 'Remis',
							data: [['Remis', <?= $n_submitted ?>], ['Non remis', <?= $n_to_submit ?>]],
							size: '80%',
							colors: ['blue', 'lightblue'],
							innerSize: '70%',
							borderColor: 'white'
						}],
						legend: {
							enabled: true
						}
					});
					</script>
        		</div>
            </div>
        </div>
		<!-- Listes corrections -->
		<div class="col-lg-4 col-md-4 col-xs-12 ">
			<div class="panel panel-primary">
				<div class="panel-heading text-center"><?= _('Pile de corrections')?>  </div>
				<div class="panel-body text-left">
					<table class="table table-striped">
						<thead>
							<th>Non remis (<?= $n_to_submit ?>)</th>
						</thead>
						<?php foreach ($p_to_submit as $student): ?>
							<tr>
								<td><?= $student->name . ' ' . $student->last_name?></td>
							</tr>
						<?php endforeach ?>
					</table>
					<table class="table table-striped">
						<thead>
							<th>Non corrigés (<?= $n_to_assess ?>)</th>
						</thead>
						<?php foreach ($p_to_assess as $student): ?>
							<tr>
								<td><?= $student->name . ' ' . $student->last_name?></td>
							</tr>
						<?php endforeach ?>
					</table>
				</div>
			</div>
		</div>
		<!-- Percentage of success -->
        <div class="col-lg-4 col-md-4 col-xs-12 ">
            <div class="panel panel-primary">
                <div class="panel-heading text-center"><?= _('Pourcentage de réussite')?>  </div>
                <div class="panel-body text-left">

                    <div id="success-chart" style="margin: 0 auto"></div>
					<script>

					// Create the chart
					Highcharts.chart('success-chart', {
					    chart: {
					        type: 'pie'
					    },
					    title: {
					        text: ''
					    },
					    subtitle: {
					        text: ''
					    },
					    yAxis: {
					        title: {
					            text: ''
							},
					    },
					    plotOptions: {
							pie: {
					            innerSize: '75%',
								showInLegend: true,
								dataLabels: {
									enabled: false
								},
					        }
					    },
					    tooltip: {
					    },
					    series: [{
							colors: ['green', 'orange', 'red'],
					        name: 'Nombre d\'élèves',
					        data: [['Réussites > 79%', <?= $success['success']?>],['Réussites > 49%',<?= $success['pass']?>],['Echecs',<?= $success['fail']?>]]
					    }],
						legend: {
							enabled: true
						}
					});
					</script>
        		</div>
            </div>
        </div>
		<!-- Percentage for each skill -->
		<div class="col-lg-6 col-md-6 col-xs-12 ">
			<div class="panel panel-primary">
				<div class="panel-heading text-center"><?= _('Résultats par pôle de compétences')?>  </div>
				<div class="panel-body text-left">

					<div id="skills-graph" style="margin: 0 auto"></div>
					<script>
			Highcharts.chart('skills-graph', {
			    chart: {
			        type: 'column'
			    },
			    title: {
			        text: ''
			    },
			    xAxis: {

			        title: {
			            text: null
			        },
					<?php foreach ($results_skills as $results_skill) {
						$skills_groups[] = $results_skill->skills_group;
						$skills_results[] =  (int) ($results_skill->user_vote / $results_skill->max_vote * 100);
					}?>
					categories: ['<?= implode("', '", $skills_groups)?>']
			    },
			    yAxis: {
			        min: 0,
					max: 100,
			        labels: {
			            overflow: 'justify'
			        },
					plotBands: [{
						from: 98,
						to: 102,
						color: 'rgba(0, 0, 0, 0)',
						label: {
							text: 'Exceptionnel',
							style: {
								color: '#808080'
							}
						}
					}, {
						from: 90,
						to: 98,
						color: 'rgba(0, 0, 255, 0.05)',
						label: {
							text: 'Excellent',
							style: {
								color: '#808080'
							}
						}
					}, {
						from: 80,
						to: 90,
						color: 'rgba(0, 0, 0, 0)',
						label: {
							text: 'Très bien',
							style: {
								color: '#808080'
							}
						}
					}, {
						from: 70,
						to: 80,
						color: 'rgba(0, 0, 255, 0.05)',
						label: {
							text: 'Bien',
							style: {
								color: '#808080'
							}
						}
					}, {
						from: 60,
						to: 70,
						color: 'rgba(0, 0, 0, 0)',
						label: {
							text: 'Satisfaisant',
							style: {
								color: '#808080'
							}
						}
					}, {
						from: 50,
						to: 60,
						color: 'rgba(0, 0, 255, 0.05)',
						label: {
							text: 'Passable',
							style: {
								color: '#808080'
							}
						}
					}]
			    },
			    tooltip: {
			        valueSuffix: ' %'
			    },
			    plotOptions: {
			        bar: {
			            dataLabels: {
			                enabled: true
			            }
			        }
			    },

			    series: [{
					data: [<?= implode(', ', $skills_results)?>],
					colorByPoint: true
			    }],
				legend: {
					enabled: false
				}
			});
				</script>

				</div>
			</div>
		</div>
		<!-- Percentage for each criterion -->
        <div class="col-lg-6 col-md-6 col-xs-12 ">
            <div class="panel panel-primary">
                <div class="panel-heading text-center"><?= _('Résultats par critère')?>  </div>
                <div class="panel-body text-left">

                    <div id="criteria-graph" style="margin: 0 auto"></div>
					<script>
					<?php foreach ($results_criteria as $criterion) {
						$criteria_names[] = $criterion->criterion;
						$criteria_results[] =  (int) ($criterion->user_vote / $criterion->max_vote * 100);
					}?>
			Highcharts.chart('criteria-graph', {
			    chart: {
			        type: 'bar'
			    },
			    title: {
			        text: ''
			    },
			    xAxis: {

			        title: {
			            text: null
			        },
					categories: ['<?= implode("', '", $criteria_names)?>']
			    },
			    yAxis: {
			        min: 0,
					max: 100,
			        labels: {
			            overflow: 'justify'
			        }
			    },
			    tooltip: {
			        valueSuffix: ' %'
			    },
			    plotOptions: {
			        bar: {
			            dataLabels: {
			                enabled: true
			            }
			        }
			    },

			    series: [{
					data: [<?= implode(', ', $criteria_results)?>],
					colorByPoint: true
			    }],
				legend: {
					enabled: false
				}
			});
				</script>
        		</div>
            </div>
        </div>

		<!-- Detailled Results -->
        <div class="col-lg-12 col-md-12 col-xs-12 ">
            <div class="panel panel-primary">
                <div class="panel-heading text-center"><?= _('Résultats détaillés')?>  </div>
                <div class="panel-body text-left">
					<table class="table table-striped">
						<thead>
					  <tr>
					    <th>Élève</th>
						<?php foreach ($students_results[0]['results'] as $header): ?>
							  <th><span data-toggle="tooltip" title="L'élève a <?= $header->cursor?>"><?= $header->criterion?></span></th>
						  <?php endforeach ?>
					    <th>Total</th>
					    <th>Remis le</th>
					  </tr>
				  </thead>
				  <?php foreach ($students_results as $student): ?>
					  <tr>
					    <td><?= $student['name'] . ' ' . $student['last_name']?></td>
					<?php foreach ($student['results'] as $result): ?>
					    <td<?= @(($result->user_vote  / $result->max_vote * 100) > 50 ? '' : ' class="text-danger"')?>><?= ($result->user_vote ? $result->user_vote . ' / ' . $result->max_vote : '-') ?></td>
					<?php endforeach ?>

						<td<?= @(($student['overall']->total_user / $student['overall']->total_max * 100) > 50 ? '' : ' class="text-danger"')?>><?= ($student['overall']->total_user ? $student['overall']->total_user . ' / ' . $student['overall']->total_max : '-')?></td>
					    <td><?= (isset($student['submitted_time']->time) ? $student['submitted_time']->time : '-')?></td>
					  </tr>
				  <?php endforeach ?>

					</table>
        		</div>
            </div>
        </div>
	<?php else: ?>
		<div class="col-lg-12 col-md-12 col-xs-12 ">
			<p><?= _('Aucun projet sélectionné')?>.</p>
		</div>
	<?php endif ?>
	<?php if( ! $this->input->get('modal')): ?>
	</div>
	<?php endif ?>



</div>
