<?php include('adm_config_menu.php'); ?>

    <main class="col-md-10" id="content">
               
          
           <form action="config.php" method="post" id="form">
               <label><?= LABEL_CONFIG_WELCOME ?></label>
               <textarea name="message_board" class="form-control" rows="12"><?= $message ?></textarea>
                <p class="help-block">HTML tags: <code style="color:gray;"><?= htmlspecialchars(ALLOWED_HTML_TAGS) ?></code> <br />Custom: <code style="color:gray;">%user_name% %user_lastname%</code></p>
                <button type="submit" class="btn btn-sm btn-primary" style="margin-right:0.5em;" name="change_message_board" >
                <?= LABEL_SUBMIT ?></button>
           </form>
                 
    </main>
    
