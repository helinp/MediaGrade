
						<?php if($this->Users_model->isAdmin()): ?>
							<!--
							ADMIN
						-->
						<aside class="hidden-xs col-md-2 sidebar hidden-print" role="navigation">

						<!-- side menu -->
							<ul class="nav sidebar-nav " data-spy="affix" data-offset-top="90">
								<li<?= ( $this->uri->segment(2) === 'dashboard'  ? ' class="active"' : '') ?>><a href="/admin/dashboard?school_year=<?= get_school_year() ?>"><span class="glyphicon glyphicon-dashboard"></span> <?= _('DASHBOARD') ?><span class="sr-only">(current)</span></a></li>
								<li class="divider" role="separator"></li>
								<li<?= ( $this->uri->segment(2) === 'projects'  ? ' class="active"' : '') ?>><a href="/admin/projects?school_year=<?= get_school_year() ?>"><span class="glyphicon glyphicon-file"></span> <?= _('PROJETS') ?></a></li>
								<li<?= ( $this->uri->segment(2) === 'grade'  ? ' class="active"' : '') ?>><a href="/admin/grade?school_year=<?= get_school_year() ?>"><span  class="glyphicon glyphicon glyphicon-pencil"></span> <?= _('CORRIGER') ?></a></li>
								<li<?= ( $this->uri->segment(2) === 'results'  ? ' class="active"' : '') ?>><a href="/admin/results?school_year=<?= get_school_year() ?>"><span  class="glyphicon glyphicon glyphicon-education"></span> <?= _('RESULTATS') ?></a></li>
								<li<?= ( $this->uri->segment(2) === 'achievements'  ? ' class="active"' : '') ?>><a href="/admin/achievements"><span  class="glyphicon glyphicon glyphicon-certificate"></span> <?= _('BADGES') ?></a></li>
								<li class="divider" role="separator"></li>
								<li<?= ( $this->uri->segment(1) === 'gallery'  ? ' class="active"' : '') ?>><a href="/gallery"><span class="glyphicon glyphicon-sunglasses"></span> <?= _('GALLERIE') ?></a></li>
								<li class="divider" role="separator"></li>
								<li<?= ( $this->uri->segment(2) === 'export'  ? ' class="active"' : '') ?>><a href="/admin/export/projects_assessments?school_year=<?= get_school_year() ?>"><span  class="glyphicon glyphicon glyphicon-export"></span> <?= _('EXPORTATIONS') ?></a></li>
								<li<?= ( $this->uri->segment(2) === 'import'  ? ' class="active"' : '') ?>><a href="/admin/import/"><span  class="glyphicon glyphicon glyphicon-import"></span> <?= _('IMPORTATIONS') ?></a></li>
								<li class="divider" role="separator"></li>
								<li <?= ( $this->uri->segment(2) === 'skills' || $this->uri->segment(2) === 'groups' || $this->uri->segment(2) === 'users' || $this->uri->segment(2) === 'terms'? 'class="active"' : '') ?>>
									<a href="#" class="dropdown-toggle" data-toggle="dropdown"><span  class="glyphicon glyphicon-cog"></span> <?= _('CONFIGURATION') ?> <span class="caret"></span></a>
									<ul class="nav dropdown-menu custom-menu">
										<li<?= ( $this->uri->segment(2) === 'users'  ? ' class="active"' : '') ?>><a href="/admin/users/students"><?= _('UTILISATEURS') ?></a></li>
										<li<?= ( $this->uri->segment(2) === 'classes'  ? ' class="active"' : '') ?>><a href="/admin/classes/classes"><?= _('CLASSES') ?></a></li>
										<li<?= ( $this->uri->segment(2) === 'config'  ? ' class="active"' : '') ?>><a href="/admin/config/terms"><?= _('CONFIGURATION') ?></a></li>
										<li<?= ( $this->uri->segment(2) === 'skills'  ? ' class="active"' : '') ?>><a href="/admin/skills"><?= _('COMPÉTENCES') ?></a></li>
									</ul>
								</li>
								<li<?= ( $this->uri->segment(2) === 'maintenance'  ? ' class="active"' : '') ?>><a href="/admin/maintenance"><span  class="glyphicon glyphicon glyphicon-wrench"></span> <?= _('SYSTÈME') ?></a></li>
							</ul>
						</aside>

						<aside class="visible-xs col-xs-2 sidebar hidden-print" role="navigation">

						<!-- side menu -->
							<ul class="nav sidebar-nav " data-spy="affix" data-offset-top="90">
								<li<?= ( $this->uri->segment(2) === 'dashboard'  ? ' class="active"' : '') ?>><a href="/admin/dashboard?school_year=<?= get_school_year() ?>"><span class="glyphicon glyphicon-dashboard"></span> <span class="sr-only">(current)</span></a></li>
								<li class="divider" role="separator"></li>
								<li<?= ( $this->uri->segment(2) === 'projects'  ? ' class="active"' : '') ?>><a href="/admin/projects?school_year=<?= get_school_year() ?>"><span class="glyphicon glyphicon-file"></span> </a></li>
								<li<?= ( $this->uri->segment(2) === 'grade'  ? ' class="active"' : '') ?>><a href="/admin/grade?school_year=<?= get_school_year() ?>"><span  class="glyphicon glyphicon glyphicon-pencil"></span> </a></li>
								<li<?= ( $this->uri->segment(2) === 'results'  ? ' class="active"' : '') ?>><a href="/admin/results?school_year=<?= get_school_year() ?>"><span  class="glyphicon glyphicon glyphicon-education"></span> </a></li>
								<li<?= ( $this->uri->segment(2) === 'achievements'  ? ' class="active"' : '') ?>><a href="/admin/achievements"><span  class="glyphicon glyphicon glyphicon-certificate"></span> </a></li>
								<li class="divider" role="separator"></li>
								<li<?= ( $this->uri->segment(1) === 'gallery'  ? ' class="active"' : '') ?>><a href="/gallery"><span class="glyphicon glyphicon-sunglasses"></span> </a></li>
								<li class="divider" role="separator"></li>
								<li<?= ( $this->uri->segment(2) === 'export'  ? ' class="active"' : '') ?>><a href="/admin/export/projects_assessments?school_year=<?= get_school_year() ?>"><span  class="glyphicon glyphicon glyphicon-export"></span> </a></li>
								<li<?= ( $this->uri->segment(2) === 'import'  ? ' class="active"' : '') ?>><a href="/admin/import/"><span  class="glyphicon glyphicon glyphicon-import"></span> </a></li>
								<li class="divider" role="separator"></li>
								<li <?= ( $this->uri->segment(2) === 'skills' || $this->uri->segment(2) === 'groups' || $this->uri->segment(2) === 'users' || $this->uri->segment(2) === 'terms'? 'class="active"' : '') ?>>
									<a href="#" class="dropdown-toggle" data-toggle="dropdown"><span  class="glyphicon glyphicon-cog"></span> <span class="caret"></span></a>
									<ul class="nav dropdown-menu custom-menu">
										<li<?= ( $this->uri->segment(2) === 'users'  ? ' class="active"' : '') ?>><a href="/admin/users/students"><?= _('UTILISATEURS') ?></a></li>
										<li<?= ( $this->uri->segment(2) === 'classes'  ? ' class="active"' : '') ?>><a href="/admin/classes/classes"><?= _('CLASSES') ?></a></li>
										<li<?= ( $this->uri->segment(2) === 'config'  ? ' class="active"' : '') ?>><a href="/admin/config/terms"><?= _('CONFIGURATION') ?></a></li>
										<li<?= ( $this->uri->segment(2) === 'skills'  ? ' class="active"' : '') ?>><a href="/admin/skills"><?= _('COMPÉTENCES') ?></a></li>
									</ul>
								</li>
								<li<?= ( $this->uri->segment(2) === 'maintenance'  ? ' class="active"' : '') ?>><a href="/admin/maintenance"><span  class="glyphicon glyphicon glyphicon-wrench"></span> </a></li>
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
								<li<?= ( $this->uri->segment(2) === 'projects'  ? ' class="active"' : '') ?>><a href="/student/projects"><span  class="glyphicon glyphicon-file"></span> <?= _('REMISES & PROJETS') ?> <?php if($this->session->not_submitted): ?> <span class="badge"><?= $this->session->not_submitted ?></span><?php endif ?></a></li>
								<li<?= ( $this->uri->segment(2) === 'results'  ? ' class="active"' : '') ?>><a href="/student/results"><span  class="glyphicon glyphicon-list-alt"></span> <?= _('RÉSULTATS') ?></a></li>
								<li<?= ( $this->uri->segment(2) === 'achievements'  ? ' class="active"' : '') ?>><a href="/student/achievements"><span  class="glyphicon glyphicon-education"></span> <?= _('MES BADGES') ?></a></li>
								<li<?= ( $this->uri->segment(1) === 'gallery' && $this->uri->segment(3) ? ' class="active"' : '') ?>><a href="/gallery/0/1"><span  class="glyphicon glyphicon-sunglasses"></span> <?= _('MA GALLERIE') ?></a></li>

								<li class="divider" role="separator"></li>
								<li<?= ( $this->uri->segment(1) === 'gallery' && ! $this->uri->segment(3) ? ' class="active"' : '') ?>><a href="/gallery"><span  class="glyphicon glyphicon-film"></span> <?= _('GALLERIE') ?></a></li>
								<li<?= ( $this->uri->segment(2) === 'class_list'  ? ' class="active"' : '') ?>><a href="/student/class_list">
									<span class="glyphicon glyphicon-th"></span> <?= _('TROMBINOSCOPE') ?><span class="sr-only">(current)</span></a></li>
								</ul>
							</aside>
						<?php endif ?>
