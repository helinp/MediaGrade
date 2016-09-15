<div id="content" class="col-xs-12 col-md-10 ">

    <div class="row chapeau">
        <div class="col-xs-12  col-md-12">
            <h1><?= _('Gestion des utilisateurs')?></h1>
        </div>
    </div>
    <div class="alert alert-danger" style="margin-top:1em;" role="alert"><?= LABEL_ADMIN_DANGER ?></div>
        <h3><?= _('Ajouter un utilisateur')?></h3>
           <form action="/admin/users/add_user" method="post">
               <table id="rows" class="table">
     	            <col width="10%">
                    <col width="15%">
                    <col width="15%">
                    <col width="15%">
                    <col width="15%">
                    <col width="15%">
                    <col width="5%">
                    <col width="5%">
     	            <thead>
	                    <tr>
		                    <th><?= LABEL_CLASS ?></th>
		                    <th><?= LABEL_LAST_NAME ?></th>
		                    <th><?= LABEL_NAME ?></th>
                            <th><?= LABEL_EMAIL ?></th>
		                    <th><?= LABEL_USERNAME ?></th>
		                    <th><?= LABEL_PASSWORD ?></th>
		                    <th></th>
		                    <th></th>
	                    </tr>
	                </thead>
	                <tbody>
                        <tr>
                            <td><input name="class" class="form-control input-sm"></td>
                            <td><input name="last_name" class="form-control input-sm" required></td>
                            <td><input name="name" class="form-control input-sm" required></td>
                            <td><input name="email" class="form-control input-sm"></td>
                            <td><input name="username" class="form-control input-sm" required></td>
                            <td><input name="password" class="form-control input-sm" type="password" required></td>
                            <td><button type="submit" name="add_user" class="btn btn-primary btn-xs" value="add_user"><span class="glyphicon glyphicon-plus"></span></button></td>
                            <td><input name="role" value="student" type="hidden"></td>
                        </tr>
                    </tbody>
                </table>
           </form>
           <h3><?= _('Liste des professeurs')?></h3>
           <form action="config.php" method="post">
               <table class="table table-striped">
                   <col width="10%">
                   <col width="15%">
                   <col width="15%">
                   <col width="15%">
                   <col width="15%">
                   <col width="15%">
                   <col width="5%">
                   <col width="5%">

                    <tbody>
                <?php foreach($admins as $class): ?>
               <?php foreach($class as $user): ?>

                        <tr>
                            <form action="/admin/users/update_user" method="post" style="display:inline;">
                                <td></td>
                                <td><input name="last_name" class="form-control input-sm" value= "<?= $user->last_name ?>"></td>
                                <td><input name="name" class="form-control input-sm" value= "<?= $user->name ?>"></td>
                                <td><input name="email" class="form-control input-sm" value= "<?= $user->email ?>"></td>
                                <td><input name="username" class="form-control input-sm" value= "<?= $user->username ?>"></td>
                                <td><input name="password" class="form-control input-sm" value= "" type="password"></td>
                                <td><button type="submit" class="btn btn-primary btn-xs" name="id" value="<?=  $user->id?>"><span class="glyphicon glyphicon-pencil"></span></button></td>
                            </form>
                            <form action="/admin/users/delete_user" method="post" style="display:inline;">
                                <?php if($user->id !== $this->session->id): ?>
                                <td><button type="submit" class="btn btn-danger btn-xs" name="id" value="<?= $user->id ?>"><span class="glyphicon glyphicon-trash"></span></button></td>
                            <?php else: ?>
                                <td></td>
                            <?php endif ?>
                            </form>
                        </tr>

               <?php endforeach?>
               <?php endforeach?>
                    </tbody>
               </table>
            </form>

           <h3><?= _('Liste des utilisateurs')?></h3>

           <?php foreach($users as $class): ?>
            <h4> <?= $class{0}->class ?></h4>


                   <table class="table table-striped">
                       <col width="10%">
                       <col width="15%">
                       <col width="15%">
                       <col width="15%">
                       <col width="15%">
                       <col width="15%">
                       <col width="5%">
                       <col width="5%">

	                    <tbody>
                   <?php foreach($class as $user): ?>

                            <tr>
                                <form action="/admin/users/update_user" method="post" style="display:inline;">
                                    <td><input name="class" class="form-control input-sm" value= "<?= $user->class ?>"></td>
                                    <td><input name="last_name" class="form-control input-sm" value= "<?= $user->last_name ?>"></td>
                                    <td><input name="name" class="form-control input-sm" value= "<?= $user->name ?>"></td>
                                    <td><input name="email" class="form-control input-sm" value= "<?= $user->email ?>"></td>
                                    <td><input name="username" class="form-control input-sm" value= "<?= $user->username ?>"></td>
                                    <td><input name="password" class="form-control input-sm" value= "" type="password"></td>
                                    <td>
                                        <button type="submit" class="btn btn-primary btn-xs" name="id" value="<?= $user->id ?>">
                                            <span class="glyphicon glyphicon-pencil"></span>
                                        </button>
                                    </td>
                                </form>
                                <form action="/admin/users/delete_user" method="post" style="display:inline;">
                                    <td>
                                        <button type="submit" class="btn btn-danger btn-xs" name="id" value="<?= $user->id ?>">
                                            <span class="glyphicon glyphicon-trash"></span>
                                        </button>
                                    </td>
                                </form>
                            </tr>

                   <?php endforeach?>
                        </tbody>
                   </table>

            <?php endforeach?>


    </div>
