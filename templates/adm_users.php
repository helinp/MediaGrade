    <aside id="projects" class="col-md-2 bs-docs-sidebar">
        <nav itemscope itemtype="http://schema.org/SiteNavigationElement">
            <ul class="list-group small" itemprop="project">
                    <li><a class="list-group-item" href="config.php?skills"><?= LABEL_SKILLS ?><span class="glyphicon glyphicon-pencil pull-right"></span></a></li>
                    <li><a class="list-group-item active" href="config.php?users"><?= LABEL_CLASS_ROLL ?><span class="glyphicon glyphicon-pencil pull-right"></span></a></li>
                    <li><a class="list-group-item" href="config.php?welcome"><?= LABEL_CONFIG_WELCOME ?><span class="glyphicon glyphicon-pencil pull-right"></a></li>
            </ul>
        </nav>
    </aside>
    
    <main class="col-md-10" id="content">
           <form action="config.php" method="post">
               <table id="rows" class="table">         
     	            <col width="14%">
                    <col width="19%">
                    <col width="19%">
                    <col width="19%">
                    <col width="19%">
                    <col width="5%">
                    <col width="5%">
     	            <thead>
	                    <tr>
		                    <th><?= LABEL_CLASS ?></th>
		                    <th><?= LABEL_LAST_NAME ?></th>
		                    <th><?= LABEL_NAME ?></th>
		                    <th><?= LABEL_USERNAME ?></th>
		                    <th><?= LABEL_PASSWORD ?></th>
		                    <th></th>
		                    <th></th>
	                    </tr>
	                </thead>
	                <tbody>
                        <tr>
                            <td><input name="class" class="form-control input-sm"></td>
                            <td><input name="last_name" class="form-control input-sm"></td>
                            <td><input name="name" class="form-control input-sm"></td>
                            <td><input name="username" class="form-control input-sm"></td>
                            <td><input name="password" class="form-control input-sm"></td>
                            <td><button type="submit***" name="add_user" class="btn btn-primary btn-xs" value="add_user"><span class="glyphicon glyphicon-plus"></span></button></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
           </form>         

           <?php foreach($users as $class): ?>
            <h4> <?= (empty($class[0]["class"]) ? LABEL_TEACHER : $class[0]["class"]) ?></h4>
           
               <form action="config.php" method="post">
                   <table class="table table-striped">
     	            <col width="14%">
                    <col width="19%">
                    <col width="19%">
                    <col width="19%">
                    <col width="19%">
                    <col width="5%">
                    <col width="5%">
	                    
	                    <tbody>
                   <?php foreach($class as $user): ?>
                            
                            <tr<?= ($user["is_staff"] == 1 ? ' class="muted"' : "") ?>>
                                <td><input name="class[<?=  $user["id"]?>]" class="form-control input-sm" value= "<?= $user["class"] ?>"></td>
                                <td><input name="last_name[<?=  $user["id"]?>]" class="form-control input-sm" value= "<?= $user["last_name"] ?>"></td>
                                <td><input name="name[<?=  $user["id"]?>]" class="form-control input-sm" value= "<?= $user["name"] ?>"></td>
                                <td><input name="username[<?=  $user["id"]?>]" class="form-control input-sm" value= "<?= $user["username"] ?>"></td>
                                <td><input name="password[<?=  $user["id"]?>]" class="form-control input-sm" value= "" type="password"></td>
                                <td><button type="submit" class="btn btn-primary btn-xs" name="update_user[]" value="<?=  $user["id"]?>"><span class="glyphicon glyphicon-pencil"></span></button></td>
                                <?= ($user["is_staff"] == 0 ? '<td><button type="submit" class="btn btn-danger btn-xs" name="del_user[]" value="del_ . $user["id"] ."><span class="glyphicon glyphicon-trash"></span></button></td>' : '<td></td>') ?>
                            </tr>  
                                  
                   <?php endforeach?> 
                        </tbody>
                   </table>
                </form>
            <?php endforeach?> 
            
                 
    </main>
    
