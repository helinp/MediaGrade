    <main class="col-md-10" id="content">
          <div class="row">
            <div class="col-md-8"> 
               <h4><?= LABEL_MY_PROFILE ?></h4>
               

               <form action="profile.php" method="post" role="form">
               <table class="table">
                   <tr>
                    <td><b><?= LABEL_LAST_NAME ?>:</b> </td><td><?= $user_data["last_name"]?></td><td></td>
                   </tr>
                   <tr>
                    <td><b><?= LABEL_NAME ?></b> </td><td><?= $user_data["name"]?></td><td></td>
                   </tr>
                   <tr>
                    <td><b><?= LABEL_CLASS ?>:</b> </td><td><?= ($_SESSION["admin"] ? LABEL_TEACHER : $user_data["class"]) ?></td><td></td>
                   </tr>                   <tr>
                       <td><b><?= LABEL_EMAIL ?>:</b> </td>
                       <td><input type="text" name="email" placeholder=" <?= LABEL_MY_MAIL ?>" value="<?= $user_data["email"]?>" /></td>
                       <td><button type="submit" name="change_email" value="1" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-pencil"></span> <?= LABEL_MODIFY ?></button> </td>
                   </tr>
                       <td colspan="3"><h4><?= LABEL_CHANGE_PASS ?></h4></td>
                       
                   </tr>
                   <tr>
                       <td><b><?= LABEL_ACTUAL_PASS ?>:</b> </td>
                       <td><input type="password" name="actual_password" placeholder=" <?= LABEL_PASSWORD ?>"  /></td>
                       <td></td>
                   </tr>
                   <tr>
                       <td><b><?= LABEL_NEW_PASS ?>:</b> </td>
                       <td><input type="password" name="new_password" placeholder=" <?= LABEL_PASSWORD ?>"  /></td>
                       <td></td>
                   </tr>
                   <tr>
                       <td><b><?= LABEL_CONFIRM_PASS ?>:</b> </td>
                       <td><input type="password" name="confirm_new_password" placeholder=" Confirmation" /></td>
                       <td><button type="submit" name="change_password" value="1" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-pencil"></span> <?= LABEL_MODIFY ?></button> </td>
                   </tr>
                </table>
               
               </form>         
            </div>
         </div>
                 
    </main>
    
