<aside id="projects" class="col-sm-3 col-md-2 sidebar-wrapper p_sidemenu">
    <nav>
        <ul class="list-group small" itemprop="project">
                    <li><a class="list-group-item<?= ($this->uri->segment(4, 0) ? '' : ' active') ?>" href="/admin/results/<?= $this->uri->segment(3, 0) ?>"><?= LABEL_ALL_PERIODS ?><span class="glyphicon glyphicon-th-list pull-right"></span></a></li>
                <?php foreach($periods as $period): ?>
                    <li><a class="list-group-item<?= ($this->uri->segment(4, 0) === $period ? ' active' : '') ?>" href="/admin/results/<?= $this->uri->segment(3, 0) ?>/<?= $period?>"><?= LABEL_PERIOD ?> <?=$period  ?><span class="glyphicon glyphicon-th-list pull-right"></span></a></li>
                <?php endforeach ?>
        </ul>
    </nav>
</aside>
