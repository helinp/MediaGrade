    <main class="col-md-10" id="content">
          <div class="row">
            <div class="col-md-8"> 
               <h3><?= $lang['MY_PROFILE'] ?></h3>
               

               <form action="profile.php" method="post" role="form">
               <table class="table">
                   <tr>
                    <td><b><?= $lang['LAST_NAME'] ?>:</b> </td><td><?= $user_data["last_name"]?></td><td></td>
                   </tr>
                   <tr>
                    <td><b><?= $lang['NAME'] ?></b> </td><td><?= $user_data["name"]?></td><td></td>
                   </tr>
                   <tr>
                    <td><b><?= $lang['CLASS'] ?>:</b> </td><td><?= ($_SESSION["admin"] ? $lang['TEACHER'] : $user_data["class"]) ?></td><td></td>
                   </tr>                   <tr>
                       <td><b><?= $lang['EMAIL'] ?>:</b> </td>
                       <td><input type="text" name="email" placeholder=" <?= $lang['MY_MAIL'] ?>" value="<?= $user_data["email"]?>" /></td>
                       <td><button type="submit" name="change_email" value="1" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-pencil"></span> <?= $lang['MODIFY'] ?></button> </td>
                   </tr>
                       <td colspan="3"><h4><?= $lang['CHANGE_PASS'] ?></h4></td>
                       
                   </tr>
                   <tr>
                       <td><b><?= $lang['ACTUAL_PASS'] ?>:</b> </td>
                       <td><input type="password" name="actual_password" placeholder=" <?= $lang['PASSWORD'] ?>"  /></td>
                       <td></td>
                   </tr>
                   <tr>
                       <td><b><?= $lang['NEW_PASS'] ?>:</b> </td>
                       <td><input type="password" name="new_password" placeholder=" <?= $lang['PASSWORD'] ?>"  /></td>
                       <td></td>
                   </tr>
                   <tr>
                       <td><b><?= $lang['CONFIRM_PASS'] ?>:</b> </td>
                       <td><input type="password" name="confirm_new_password" placeholder=" Confirmation" /></td>
                       <td><button type="submit" name="change_password" value="1" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-pencil"></span> <?= $lang['MODIFY'] ?></button> </td>
                   </tr>
                </table>
               
               </form>         
            </div>
         </div>
                 
    </main>
    
