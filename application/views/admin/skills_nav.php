<aside id="projects" class="col-sm-3 col-md-2 sidebar-wrapper p_sidemenu">
    <?php if( ! empty($_SESSION['avatar'])):?>
<div style="margin: 0 auto;">
    <img src="<?= $_SESSION['avatar']?>" alt="user_avatar" class=" center-block img-circle img-responsive" style="text-align:center;width:100px" />
</div>
    <?php endif ?>
<div style="margin-top:1em;"></div>
    <nav>
        <ul class="list-group small">
            <li>
                <a class="list-group-item" href="/admin/skills_groups">
                    <?= _('Gérer les groupes de compétences') ?><span class="glyphicon glyphicon-pencil pull-right"></span>
                </a>
            </li>
            <li>
                <a class="list-group-item" href="/admin/skills">
                    <?= _('Gérer les compétences') ?><span class="glyphicon glyphicon-pencil pull-right"></span>
                </a>
            </li>
        </ul>
    </nav>


</aside>
