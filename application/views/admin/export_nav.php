<aside id="projects" class="col-sm-3 col-md-2 sidebar-wrapper p_sidemenu">
    <nav itemscope itemtype="http://schema.org/SiteNavigationElement">
        <ul class="list-group small" itemprop="project">
            <li><a class="list-group-item<?= ($this->uri->segment(3, 0) == NULL ? ' active' : '')?>" href="export.php">
                    <?= LABEL_ALL_PERIODS ?><span class="glyphicon glyphicon-th-list pull-right"></span>
                </a>
            </li>
            <?php foreach($periodes as $periode): ?>
            <li>
                <a class="list-group-item<?= ($this->uri->segment(3, 0) == $periode ? ' active' : '')?>" href="/admin/export/<?= $periode ?>">
                    <?= LABEL_PERIOD ?> <?= $periode ?><span class="glyphicon glyphicon-th-list pull-right"></span>
                </a>
            </li>
            <?php endforeach ?>
        </ul>
    </nav>
</aside>
