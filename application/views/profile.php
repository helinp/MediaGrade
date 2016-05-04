<main class="col-sm-8 col-md-9" id="content">
   <h2><?= LABEL_MY_PROFILE ?></h2>
   <hr style="margin-top:0;" />
   <form action="profile/update" method="post" role="form">
      <table class="table profile">
        <colgroup>
          <col style="width:30%">
          <col style="width:30%">
          <col style="width:10%">
        </colgroup>
        <td colspan="3">
           <h3><?= _('Mes informations') ?></h3>
        </td>
         <tr>
            <td><b><?= LABEL_LAST_NAME ?>:</b> </td>
            <td><?= $user_data->last_name?></td>
            <td></td>
         </tr>
         <tr>
            <td><b><?= LABEL_NAME ?></b> </td>
            <td><?= $user_data->name?></td>
            <td></td>
         </tr>
         <tr>
            <td><b><?= LABEL_CLASS ?>:</b> </td>
            <td><?= ($_SESSION['role'] === 'admin' ? LABEL_TEACHER : $user_data->class) ?></td>
            <td></td>
         </tr>
         <tr>
            <td><b><?= LABEL_EMAIL ?>:</b> </td>
            <td><input type="text" name="email" placeholder=" <?= LABEL_MY_MAIL ?>" value="<?= $user_data->email?>" /></td>
            <td></td>
         </tr>
         <tr class="top-line">
            <td></td>
            <td></td>
            <td><button type="submit" name="change_email" value="1" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-pencil"></span> <?= LABEL_MODIFY ?></button> </td>
         </tr>
     </form>
     <form action="profile/update" method="post" role="form">
         <td colspan="3">
            <h3><?= LABEL_CHANGE_PASS ?></h3>
         </td>
         </tr>
         <tr>
            <td><b><?= LABEL_ACTUAL_PASS ?>:</b> </td>
            <td><input type="password" name="actual_password" placeholder=" <?= LABEL_PASSWORD ?>"  disabled /></td>
            <td></td>
         </tr>
         <tr>
            <td><b><?= LABEL_NEW_PASS ?>:</b> </td>
            <td><input type="password" name="new_password" placeholder=" <?= LABEL_PASSWORD ?>" disabled  /></td>
            <td></td>
         </tr>
         <tr>
            <td><b><?= LABEL_CONFIRM_PASS ?>:</b> </td>
            <td><input type="password" name="confirm_new_password" placeholder=" Confirmation" disabled /></td>
            <td></td>
         </tr>
         <tr class="top-line">
           <td> </td>
           <td></td>
           <td><button type="submit" name="change_password" value="1" class="btn btn-primary btn-xs" disabled><span class="glyphicon glyphicon-pencil"></span> <?= LABEL_MODIFY ?></button> </td>
       </form>
       <form action="/profile/upload" method="post" role="form" enctype="multipart/form-data">
         </tr>
         </tr>
         <td colspan="3">
            <h3><?= _('Mon avatar') ?></h3>
         </td>
         </tr>
         <tr>
            <td colspan="3">L'image doit être en format <em>carré</em>, JPG ou PNG et peser moins de 200Ko.</td>

         </tr>
         <tr>
            <td colspan="3"><input type="file" name="avatar_file" accept="image/*" />
                <?php if (isset($error)): ?>
                <div class="alert alert-danger" style="margin-top:1em" role="alert">
                  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                  <span class="sr-only">Error:</span><?= strip_tags($error) ?>
                 </div>
             <?php endif ?>
            </td>

         </tr>
         <tr class="top-line">
            <td></td>
            <td></td>
            <td><button type="submit" name="upload_avatar" value="1" class="btn btn-primary btn-xs">
               <span class="glyphicon glyphicon-save"></span> <?= _('Téléverser')?></button>

            </td>
         </tr>
         </tr>
     </form>
     <form action="profile/update" method="post" role="form">
         <td colspan="3">
            <h3><?= _('Notifications par courriel') ?></h3>
         </td>
         </tr>
         <tr>
            <td  colspan="3">
                <p><?= _('Je souhaite:')?></p>
               <div class="checkbox">
                  <label>
                  <?= @form_checkbox('submit_confirmation', 'true', $preferences_data['submit_confirmation']) . _('Recevoir un courriel de confirmation lorsque je remets une réalisation.')?>
                  </label>
               </div>
               <div class="checkbox">
                  <label>
                  <?= @form_checkbox('assessment_confirmation', 'true', $preferences_data['assessment_confirmation']) . _('Recevoir un courriel lorsque l\'une de mes réalisations est évaluée.') ?>
                  </label>
               </div>
            </td>

         </tr>
         <tr class="top-line">
            <td>
            </td>
            <td></td>
            <td><button type="submit" name="change_mail_preferences" value="1" class="btn btn-primary btn-xs">
               <span class="glyphicon glyphicon-pencil"></span> <?= LABEL_MODIFY ?></button>
            </td>
         </tr>
      </table>
   </form>
</main>
