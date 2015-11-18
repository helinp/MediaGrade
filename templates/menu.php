   <nav class="navbar navbar-default navbar-fixed-top" id="menu">
       <div class="container" <?= ($_SESSION["admin"] ? 'style="background-color:#482084;"' : '') ?>>     
             <!-- <img class="navbar-brand" alt="MediaGrade" src="/img/logo.png" />-->
             <div class="navbar-header">
             <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
             </button>
            <a class="navbar-brand visible-xs" href="#">MediaGrade</a> 
            </div>
               
            <div id="navbar" class="navbar-collapse collapse">
               <ul class="nav navbar-nav">
               <?php if($_SESSION["admin"]):?>
                    <li><a href="admin.php"><span class="glyphicon glyphicon-film"></span> <?=$lang['MANAGE_PROJECTS']?> </a></li>
                    <li><a href="rating.php"><span class="glyphicon glyphicon-list-alt"></span> <?=$lang['RATE']?></a></li>
                    <li>
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-th-list"></span>  <?=$lang['RESULTS']?>  <span class="caret"></span></a>
                        <ul class="dropdown-menu dropdown-menu">
                               
                            <?php foreach($classes as $class): ?> 
                            <li><a href="admin.php?results=<?= trim($class["class"], " ") ?>"><?= $class["class"] ?></a></li>
                            <?php endforeach ?>
                        </ul>
                        
                        
                    </li>
               <?php else: ?>
                    <li><a href="index.php"><span class="glyphicon glyphicon-film"></span> <?=$lang['SUBMIT']?></a></li>
                    <li><a href="gradebook.php"><span class="glyphicon glyphicon-list-alt"></span> <?=$lang['GRADE_BOOK']?></a></li>
                    <li><a href="gallery.php?my"><span class="glyphicon glyphicon-briefcase"></span> <?=$lang['MY_GALLERY']?></a></li>
               <?php endif ?>
                    <li><a href="gallery.php"><span class="glyphicon glyphicon-sunglasses"></span> <?=$lang['GALLERY']?></a></li>
               </ul>
               <ul class="nav navbar-nav navbar-right">
                    <li><a href="config.php"><span class="glyphicon glyphicon-briefcase"></span> <?=$lang['CONFIG']?></a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span> <?= $username[0]["name"] . " " . $username[0]["last_name"] ?> <span class="caret"></span></a>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li><a href="profile.php">Profil</a></li>
                            <li class="divider"></li>
                            <li><a href="logout.php"><span class="glyphicon glyphicon-log-in"></span> <?=$lang['LOGOUT']?></a></li>
                        </ul>
                    </li>
               </ul>
            </div>    
        </div>
   </nav> 
