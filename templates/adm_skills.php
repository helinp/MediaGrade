<?php include_once('adm_config_menu.php'); ?>

    <main class="col-md-10" id="content">
               
           <form action="config.php" method="post" role="form">
               <table id="rows" class="table">         
     	            <col width="5%">
                    <col width="90%">
                    <col width="5%">
     	            <thead>
	                    <tr>
		                    <th>ID</th>
		                    <th><?= LABEL_SKILLS ?></th>
		                    <th></th>
	                    </tr>
	                </thead>
	                <tbody>
                        <tr>
                            <td><input name="skill_id" class="form-control input-sm"></td>
                            <td><textarea name="skill" class="form-control input-sm" cols="50" rows="3" fixed></textarea></td>
                            <td><button type="submit" class="btn btn-primary btn-xs" value="add"><span class="glyphicon glyphicon-plus"></span></button></td>
                        </tr>
                    </tbody>
                </table>
           </form>         

           <form action="config.php" method="post" role="form">
               <table id="rows" class="table table-striped">
         	            <col width="5%">
                        <col width="90%">
                        <col width="5%">
	                <tbody>
               <?php foreach($skills as $skill): ?>
                        <tr>
                            <td><?= $skill["skill_id"] ?></td>
                            <td><?= $skill["skill"] ?></td>
                            <td>
                                <button type="submit" class="btn btn-danger btn-xs" name="del_skill[]" value="<?=  $skill["skill_id"] . "+++" . $skill["skill"] ?>"><span class="glyphicon glyphicon-trash"></span></button>
                            </td>
                        </tr>
               <?php endforeach?> 
                    </tbody>
               </table>
           </form> 
           
                 
    </main>
    
