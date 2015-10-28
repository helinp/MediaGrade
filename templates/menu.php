   <nav class="navbar navbar-default navbar-fixed-top" id="menu">
       <div class="container">     
           <!-- <img class="navbar-brand" alt="MediaGrade" src="/img/logo.png" />-->
           <ul class="nav navbar-nav">
                <li <?php if(isset($_GET["$title"]))  {if($title == "Projets") echo("class=\"active\"");}?>><a href="index.php"><span class="glyphicon glyphicon-film"></span> <?=$lang['SUBMIT']?></a></li>
                <li <?php if(isset($_GET["$title"]))  {if($title == "Mes compétences") echo("class=\"active\"");}?>><a href="gradebook.php"><span class="glyphicon glyphicon-list-alt"></span> <?=$lang['GRADE_BOOK']?></a></li>
                <li <?php if(isset($_GET["$title"]))  {if($title == "Gallerie") echo("class=\"active\"");}?>><a href="gallery.php?my"><span class="glyphicon glyphicon-briefcase"></span> <?=$lang['MY_GALLERY']?></a></li>
                <li <?php if(isset($_GET["$title"]))  {if($title == "Gallerie") echo("class=\"active\"");}?>><a href="gallery.php"><span class="glyphicon glyphicon-sunglasses"></span> <?=$lang['GALLERY']?></a></li>
            </ul>
            
            <ul class="nav navbar-nav navbar-right">
                <li><a href="#"><span class="glyphicon glyphicon-user"></span> <?= $username[0]["name"] . " " . $username[0]["last_name"] ?></a></li>
                <li><a href="logout.php"><span class="glyphicon glyphicon-log-in"></span> <?=$lang['LOGOUT']?></a></li>
            </ul>
            
        </div>
   </nav> 
