
			<?php if($this->session->role == 'admin'): ?>

			<aside class="col-xs-12 col-md-2 sidebar hidden-print" role="navigation">
			    <!-- side menu -->
			    <ul class="nav sidebar-nav " data-spy="affix" data-offset-top="90">
			        <li<?= ( $this->uri->segment(2) === 'dashboard'  ? ' class="active"' : '') ?>><a href="/admin/dashboard?school_year=<?= get_school_year() ?>"><span class="glyphicon glyphicon-dashboard"></span> <?= _('DASHBOARD') ?><span class="sr-only">(current)</span></a></li>
					<li<?= ( $this->uri->segment(1) === 'student-details'  ? ' class="active"' : '') ?>><a href="/admin/student_details"><span  class="glyphicon glyphicon-search"></span> <?= _('DÉTAIL PAR ELÈVE') ?></a></li>
					<li class="divider" role="separator"></li>
			        <li<?= ( $this->uri->segment(2) === 'projects'  ? ' class="active"' : '') ?>><a href="/admin/projects?school_year=<?= get_school_year() ?>"><span class="glyphicon glyphicon-file"></span> <?= _('PROJETS') ?></a></li>
			        <li<?= ( $this->uri->segment(2) === 'grade'  ? ' class="active"' : '') ?>><a href="/admin/grade?school_year=<?= get_school_year() ?>"><span  class="glyphicon glyphicon glyphicon-pencil"></span> <?= _('CORRIGER') ?></a></li>
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
			        <li<?= ( $this->uri->segment(2) === 'export'  ? ' class="active"' : '') ?>><a href="/admin/export?school_year=<?= get_school_year() ?>"><span  class="glyphicon glyphicon-book"></span> <?= _('EXPORTATION PDF') ?></a></li>
			        <li<?= ( $this->uri->segment(1) === 'gallery'  ? ' class="active"' : '') ?>><a href="/gallery"><span class="glyphicon glyphicon-sunglasses"></span> <?= _('GALLERIE') ?></a></li>
			        <li class="divider" role="separator"></li>
			        <li <?= ( $this->uri->segment(2) === 'skills' || $this->uri->segment(2) === 'skills_groups' || $this->uri->segment(2) === 'users' || $this->uri->segment(2) === 'terms'? 'class="active"' : '') ?>>
			        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span  class="glyphicon glyphicon-warning-sign"></span> <?= _('CONFIG') ?> <span class="caret"></span></a>
			        <ul class="nav dropdown-menu custom-menu">
			                <li><a href="/admin/skills"><?= _('COMPÉTENCES') ?></a></li>
			                <li><a href="/admin/skills_groups"><?= _('GROUPES DE CPT') ?></a></li>
			                <li><a href="/admin/users"><?= _('ÉLÈVES') ?></a></li>
			                <li><a href="/admin/terms"><?= _('PÉRIODES') ?></a></li>
			         		<li><a href="/admin/welcome_message"><?= _('ACCUEIL') ?></a></li>
			    	</ul>
			    </li>
			    <li<?= ( $this->uri->segment(2) === 'settings'  ? ' class="active"' : '') ?>><a href="/admin/settings"><span  class="glyphicon glyphicon-wrench"></span> <?= _('SYSTÉME') ?></a></li>
			  	</ul>
			</aside>

			<?php else: ?>

			<aside class="col-xs-12 col-md-2 sidebar" >
			    <!-- side menu -->
			    <ul class="nav nav-sidebar" data-spy="affix" data-offset-top="90">
			        <li<?= ( $this->uri->segment(1) === 'projects' && !$this->uri->segment(2) ? ' class="active"' : '') ?>><a href="/projects"><span class="glyphicon glyphicon-dashboard"></span> <?= _('DASHBOARD') ?><span class="sr-only">(current)</span></a></li>
			        <li class="divider" role="separator"></li>
			        <li<?= ( $this->uri->segment(2) === 'overview'  ? ' class="active"' : '') ?>><a href="/projects/overview"><span  class="glyphicon glyphicon-file"></span> <?= _('REMISES & PROJETS') ?></a></li>
			        <li<?= ( $this->uri->segment(1) === 'gallery' && $this->uri->segment(2) !== 'student' ? ' class="active"' : '') ?>><a href="/gallery"><span  class="glyphicon glyphicon-film"></span> <?= _('GALLERIE') ?></a></li>
			        <li<?= ( $this->uri->segment(1) === 'gallery' && $this->uri->segment(2) == 'student'  ? ' class="active"' : '') ?>><a href="/gallery/student"><span  class="glyphicon glyphicon-sunglasses"></span> <?= _('MA GALLERIE') ?></a></li>
			        <li class="divider" role="separator"></li>
			    </ul>
			</aside>
			<?php endif ?>
