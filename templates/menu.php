   <nav class="navbar navbar-default navbar-fixed-top" id="menu">
       <div class="container" <?= (@$_SESSION["admin"] ? 'style="background-color:#482084;"' : '') ?>>     
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
               <?php if(@$_SESSION["admin"]):?>
                    <li><a href="projects.php"><span class="glyphicon glyphicon-film"></span> <?= LABEL_MANAGE_PROJECTS ?> </a></li>
                    <li><a href="grade.php"><span class="glyphicon glyphicon-list-alt"></span> <?= LABEL_RATE ?></a></li>
                    <li>
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-th-list"></span>  <?= LABEL_RESULTS ?>  <span class="caret"></span></a>
                        <ul class="dropdown-menu dropdown-menu">
                               
                            <?php foreach($classes as $class): ?> 
                            <li><a href="results.php?class=<?= trim($class["class"], " ") ?>"><?= $class["class"] ?></a></li>
                            <?php endforeach ?>
                        </ul>
                        
                        
                    </li>
               <?php else: ?>
                    <li><a href="index.php"><span class="glyphicon glyphicon-film"></span> <?= LABEL_SUBMIT ?></a></li>
                    <li><a href="gradebook.php"><span class="glyphicon glyphicon-list-alt"></span> <?=LABEL_GRADE_BOOK ?></a></li>
                    <li><a href="gallery.php?my"><span class="glyphicon glyphicon-briefcase"></span> <?=LABEL_MY_GALLERY ?></a></li>
               <?php endif ?>
                    <li><a href="gallery.php"><span class="glyphicon glyphicon-sunglasses"></span> <?=LABEL_GALLERY ?></a></li>
               </ul>
               <ul class="nav navbar-nav navbar-right">
               <?php if(@$_SESSION["admin"]):?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-cog"></span> <?=LABEL_CONFIG ?> <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="config.php?skills"><?= LABEL_SKILLS ?></a></li>
                            <li><a href="config.php?users"><?= LABEL_CLASS_ROLL ?></a></li>
                            <li><a href="config.php?welcome"><?= LABEL_CONFIG_WELCOME ?></a></li>

                        </ul>
                    </li>
                    
               <?php endif ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span> <?= $_SESSION["name"] . " " .$_SESSION["last_name"] ?> <span class="caret"></span></a>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li><a href="profile.php"><?= LABEL_MY_PROFILE ?></a></li>
                            <li class="divider"></li>
                            <li><a href="logout.php"><span class="glyphicon glyphicon-log-in"></span> <?= LABEL_LOGOUT ?></a></li>
                        </ul>
                    </li>
               </ul>
            </div>    
        </div>
   </nav> 
