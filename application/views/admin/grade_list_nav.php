<aside id="projects" class="col-sm-3 col-md-2 sidebar-wrapper p_sidemenu">
    <nav>
        <ul class="list-group small" itemprop="project">
            <li>
                <a class="list-group-item<?= ($this->uri->segment(3, 0) == NULL ? ' active' : '')?>" href="/admin/grade/">
                <?= LABEL_EVERY_CLASSES ?><span class="glyphicon glyphicon-pencil pull-right">
                </a>
            </li>
            <?php foreach($classes as $class): ?>
            <?php if(!empty($class)): ?>
            <li>
                <a class="list-group-item<?= ($this->uri->segment(3, 0) == $class ? ' active' : '')?>" href="/admin/grade/<?= trim($class, " ")?>">
                <?= $class ?><span class="glyphicon glyphicon-pencil pull-right">
                </a>
            </li>
            <?php endif ?>
            <?php endforeach ?>
        </ul>
    </nav>
</aside>
