
 <div class="row">
    <aside id="projects" class="col-md-2 bs-docs-sidebar">
    <?php foreach ($projects as $project):?>
        <nav itemscope itemtype="http://schema.org/SiteNavigationElement">
            <ul  class="list-group small" itemprop="project">
                    <li><h4><?= $project["project_name"]?></h4></summary></li>
                    <li>
                            <ul>
                                <li itemprop="<?=$lang['INSTRUCTIONS']?>"><a class="list-group-item <?php if(isset($_GET["project"])) {if($_GET["project"] == $project["project_id"]){echo("active");}};?>" href="?project=<?= $project["project_id"]?>"><span class="glyphicon glyphicon-chevron-right pull-right"></span><?=$lang['INSTRUCTIONS']?></a></li>
                                <li itemprop="<?=$lang['SUBMIT']?>"> <a class="list-group-item <?php if(isset($_GET["submit"]))   {if($_GET["submit"] == $project["project_id"]){echo("active");}};?>"  href="?submit=<?= $project["project_id"]?>"><span class="glyphicon glyphicon-chevron-right pull-right"></span><?=$lang['SUBMIT']?></a></li>
                                <li itemprop="<?=$lang['RESULTS']?>"><a class="list-group-item <?php if(isset($_GET["results"])) {if($_GET["results"] == $project["project_id"]){echo("active");}};?>"  href="?results=<?= $project["project_id"]?>"><span class="glyphicon glyphicon-chevron-right pull-right"></span><?=$lang['RESULTS']?></a></li>
                            </ul>
                    </li>
            </ul>
        </nav>
    <?php endforeach?>    
    </aside>
  



