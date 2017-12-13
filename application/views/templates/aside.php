
				<?php if($this->session->role == 'admin'): ?>
					<!--
					ADMIN
				-->
				<aside class="col-xs-12 col-md-2 sidebar hidden-print" role="navigation">
					<!-- side menu -->
					<ul class="nav sidebar-nav " data-spy="affix" data-offset-top="90">
						<li<?= ( $this->uri->segment(2) === 'dashboard'  ? ' class="active"' : '') ?>><a href="/admin/dashboard?school_year=<?= get_school_year() ?>"><span class="glyphicon glyphicon-dashboard"></span> <?= _('DASHBOARD') ?><span class="sr-only">(current)</span></a></li>
						<li class="divider" role="separator"></li>
						<li<?= ( $this->uri->segment(1) === 'student-details'  ? ' class="active"' : '') ?>><a href="/admin/student/details"><span  class="glyphicon glyphicon-search"></span> <?= _('DÉTAIL PAR ÉLÈVE') ?></a></li>
						<li<?= ( $this->uri->segment(1) === 'students_overview'  ? ' class="active"' : '') ?>><a href="/admin/students_overview?school_year=<?= get_school_year() ?>">
							<span class="glyphicon glyphicon-th"></span> <?= _('VUE D\'ENSEMBLE') ?><span class="sr-only">(current)</span></a>
						</li>
						<li class="divider" role="separator"></li>
						<li<?= ( $this->uri->segment(2) === 'projects'  ? ' class="active"' : '') ?>><a href="/admin/projects?school_year=<?= get_school_year() ?>"><span class="glyphicon glyphicon-file"></span> <?= _('PROJETS') ?></a></li>
						<li<?= ( $this->uri->segment(2) === 'project_stats'  ? ' class="active"' : '') ?>><a href="/admin/project/statistics"><span class="glyphicon glyphicon-stats"></span> <?= _('STATISTIQUES') ?></a></li>
						<li<?= ( $this->uri->segment(2) === 'grade'  ? ' class="active"' : '') ?>><a href="/admin/grade?school_year=<?= get_school_year() ?>"><span  class="glyphicon glyphicon glyphicon-pencil"></span> <?= _('CORRIGER') ?></a></li>
						<li class="divider" role="separator"></li>
						<li<?= ( $this->uri->segment(2) === 'results'  ? ' class="active"' : '') ?>>
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><span  class="glyphicon glyphicon-education"></span> <?= _('RÉSULTATS') ?> <span class="caret"></span></a>
						<ul class="nav dropdown-menu custom-menu">
							<?php foreach($classes as $class): ?>
								<li>
									<a href="/admin/results/<?= $class ?>?school_year=<?= get_school_year() ?>"><?= $class ?></a>
								</li>
							<?php endforeach ?>
						</ul>
					</li>
					<li<?= ( $this->uri->segment(2) === 'export'  ? ' class="active"' : '') ?>>
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><span  class="glyphicon glyphicon glyphicon-export"></span> <?= _('EXPORTATIONS') ?> <span class="caret"></span></a>
					<ul class="nav dropdown-menu custom-menu">
						<li><a href="/admin/export/projects_assessments?school_year=<?= get_school_year() ?>"><span  class="glyphicon glyphicon-book"></span> <?= _('ÉVALUATIONS') ?></a></li>
						<li<?= (PHANTOMJS_SERVER ? '' : ' class="inactive"')?>><a href="/admin/export/students_report?school_year=<?= get_school_year() ?>"><span  class="glyphicon glyphicon-user"></span> <?= _('FICHE ÉLÈVE') ?></a></li>
						<li><a href="/admin/export/lessons?school_year=<?= get_school_year() ?>"><span  class="glyphicon glyphicon-book"></span> <?= _('LECONS') ?></a></li>
					</ul>
				</li>

				<li<?= ( $this->uri->segment(1) === 'gallery'  ? ' class="active"' : '') ?>><a href="/gallery"><span class="glyphicon glyphicon-sunglasses"></span> <?= _('GALLERIE') ?></a></li>
				<li class="divider" role="separator"></li>
				<li <?= ( $this->uri->segment(2) === 'skills' || $this->uri->segment(2) === 'groups' || $this->uri->segment(2) === 'users' || $this->uri->segment(2) === 'terms'? 'class="active"' : '') ?>>
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><span  class="glyphicon glyphicon-wrench"></span> <?= _('CONFIG') ?> <span class="caret"></span></a>
					<ul class="nav dropdown-menu custom-menu">
						<li<?= ( $this->uri->segment(2) === 'users'  ? ' class="active"' : '') ?>><a href="/admin/users"><?= _('ÉLÈVES') ?></a></li>
						<li<?= ( $this->uri->segment(2) === 'achievements'  ? ' class="active"' : '') ?>><a href="/admin/achievements"><?= _('BADGES') ?></a></li>
						<li<?= ( $this->uri->segment(2) === 'welcome_message'  ? ' class="active"' : '') ?>><a href="/admin/config/welcome_message"><?= _('ACCUEIL') ?></a></li>
						<li<?= ( $this->uri->segment(2) === 'terms'  ? ' class="active"' : '') ?>><a href="/admin/config/terms"><?= _('PÉRIODES') ?></a></li>
						<li<?= ( $this->uri->segment(2) === 'skills'  ? ' class="active"' : '') ?>><a href="/admin/skills"><?= _('COMPÉTENCES') ?></a></li>
						<li<?= ( $this->uri->segment(2) === 'skills'  ? ' class="active"' : '') ?>><a href="/admin/skills/groups"><?= _('PÔLES DE CPT') ?></a></li>
						<li<?= ( $this->uri->segment(2) === 'settings'  ? ' class="active"' : '') ?>><a href="/admin/config/system"><?= _('SYSTÉME') ?></a></li>
						<li<?= ( $this->uri->segment(2) === 'maintenance'  ? ' class="active"' : '') ?>><a href="/admin/maintenance"><?= _('MAINTENANCE') ?></a></li>
					</ul>
				</li>
				</ul>
				</aside>

				<?php else: ?>
					<!--
					STUDENT
				-->
				<aside class="col-xs-12 col-md-2 sidebar" >
					<!-- side menu -->
					<ul class="nav nav-sidebar" data-spy="affix" data-offset-top="90">
						<li<?= ( $this->uri->segment(2) === 'dashboard' && !$this->uri->segment(2) ? ' class="active"' : '') ?>><a href="/student/dashboard"><span class="glyphicon glyphicon-dashboard"></span> <?= _('DASHBOARD') ?><span class="sr-only">(current)</span></a></li>
						<li class="divider" role="separator"></li>
						<li<?= ( $this->uri->segment(2) === 'projects'  ? ' class="active"' : '') ?>><a href="/student/projects"><span  class="glyphicon glyphicon-file"></span> <?= _('REMISES & PROJETS') ?></a></li>
						<li<?= ( $this->uri->segment(2) === 'achievements'  ? ' class="active"' : '') ?>><a href="/student/achievements"><span  class="glyphicon glyphicon-education"></span> <?= _('MES BADGES') ?></a></li>
						<li<?= ( $this->uri->segment(2) === 'gallery' ? ' class="active"' : '') ?>><a href="/student/gallery"><span  class="glyphicon glyphicon-sunglasses"></span> <?= _('MA GALLERIE') ?></a></li>

						<li class="divider" role="separator"></li>
						<li<?= ( $this->uri->segment(1) === 'gallery' && ! $this->uri->segment(2) ? ' class="active"' : '') ?>><a href="/gallery"><span  class="glyphicon glyphicon-film"></span> <?= _('GALLERIE') ?></a></li>
						<li<?= ( $this->uri->segment(1) === 'students_overview'  ? ' class="active"' : '') ?>><a href="/student/class_list">
							<span class="glyphicon glyphicon-th"></span> <?= _('MA CLASSE') ?><span class="sr-only">(current)</span></a></li>


						</ul>
					</aside>
				<?php endif ?>
