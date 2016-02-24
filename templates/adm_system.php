<?php include_once('adm_config_menu.php'); ?>


    <main class="col-md-10" id="content">
               
              <div class="col-md-6">
              
                  <form action="config.php" method="post">
                      <h4><?= LABEL_MAIL_TEST ?></h4>
                      <label class="control-label"><?= LABEL_SUBJECT ?></label>
                      <div class="form-group"> 
                          <input name="subject" value="<?= LABEL_ITS_A_TEST ?>">
                      </div> 
                      <div class="form-group">                          
                          <label class="control-label"><?= LABEL_MESSAGE ?></label> 
                          <textarea rows="5" type="text" name="body"><?= LABEL_ITS_A_TEST ?></textarea>
                          <input type="submit" name="send_mail_test" value="<?= LABEL_SEND_ME_A_MAIL ?>" />
                      </div> 
                  </form>
                  
                  <h4><?= LABEL_FREE_DISK_SPACE ?></h4>
                  <p><?= LABEL_FREE ?>: <em><?= $disk_space['free'] ?></em>. <?= LABEL_USED ?>: <em><?= $disk_space['total'] ?></em>.</p>
                  
                  <div class="progress">
                      <div class="progress-bar" role="progressbar" aria-valuenow="<?= $disk_space['per_used'] ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $disk_space['per_used'] ?>%">
                        <?= $disk_space['per_used'] ?> %
                      </div>
                  </div>
                  
              </div>
              <div class="col-md-6">
              </div>
          
          
                 
    </main>
    
