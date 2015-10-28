 <div class="row">
    <aside id="projects" class="col-md-2 bs-docs-sidebar">
        <nav itemscope itemtype="http://schema.org/SiteNavigationElement">
            <ul class="list-group small" itemprop="project">
                    <li><a class="list-group-item" href="config.php?skills">Compétences<span class="glyphicon glyphicon-pencil pull-right"></span></a></li>
                    <li><a class="list-group-item" href="config.php?users">Liste élèves<span class="glyphicon glyphicon-pencil pull-right"></span></a></li>
            </ul>
        </nav>
    </aside>
    
    <main class="col-md-10" id="content">
           
           <form action="config.php" method="post" role="form">
               <table id="rows" class="table">         
     	            <col width="19%">
                    <col width="19%">
                    <col width="19%">
                    <col width="19%">
                    <col width="19%">
                    <col width="5%">
     	            <thead>
	                    <tr>
		                    <th>Classe</th>
		                    <th>Nom</th>
		                    <th>Prénom</th>
		                    <th>Pseudo</th>
		                    <th>Mot de passe</th>
		                    <th></th>
	                    </tr>
	                </thead>
	                <tbody>
                        <tr>
                            <td><input name="class" class="form-control input-sm"></td>
                            <td><input name="lastname" class="form-control input-sm"></td>
                            <td><input name="name" class="form-control input-sm"></td>
                            <td><input name="username" class="form-control input-sm"></td>
                            <td><input name="password" class="form-control input-sm"></td>

                            <td><button type="name" class="btn btn-primary btn-xs" value="add"><span class="glyphicon glyphicon-plus"></span></button></td>
                        </tr>
                    </tbody>
                </table>
           </form>         

           <?php foreach($users as $class): ?>
            <h4 data-toggle="collapse" data-target="#rows_<?= trim($class[0]["class"], " ")?>"><span class="panel-title glyphicon glyphicon-circle-arrow-down"></span> <?php if(empty($class[0]["class"])) {echo("Admin");} else {echo($class[0]["class"]);}; ?></h4>
           
           <div class="panel-body collapse" id="rows_<?= trim($class[0]["class"], " ")?>">
               <form action="config.php" method="post" role="form">
                   <table class="table table-striped">
         	            <col width="19%">
                        <col width="19%">
                        <col width="19%">
                        <col width="19%">
                        <col width="19%">
                        <col width="5%">
	                    
	                    <tbody>
                   <?php foreach($class as $user): ?>
                            
                            <tr<?php if($user["is_staff"] == 1) echo(' class="info"')?>>
                                <td><input name="class" class="form-control input-sm" value = "<?= $user["class"] ?>"></td>
                                <td><input name="lastname" class="form-control input-sm" value = "<?= $user["last_name"] ?>"></td>
                                <td><input name="name" class="form-control input-sm" value = "<?= $user["name"] ?>"></td>
                                <td><input name="username" class="form-control input-sm" value = "<?= $user["username"] ?>"></td>
                                <td><input name="password" class="form-control input-sm" value = "<?= $user["hash"] ?>"  type="password"></td>
                                <td><button type="submit" class="btn btn-danger btn-xs" name="del[]" value ="<?=  "del_" . $user["id"] ?>"><span class="glyphicon glyphicon-trash"></span></button></td>
                            </tr>
                                  
                   <?php endforeach?> 
                        </tbody>
                   </table>
                
                   <button type="submit" class="btn btn-primary btn-xs" name="commit[]" value ="commit_<?=  $user["id"]?>"><span class="glyphicon glyphicon-save"></span> Commit changes</button>
                
                </form>
            </div>
            <?php endforeach?> 
            
           
                 
    </main>
    