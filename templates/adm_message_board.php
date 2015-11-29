    <script src="js/tinymce/tinymce.min.js"></script>
    <script>
    
        tinymce.init({
          selector: 'textarea',
          plugins: [
            'advlist autolink lists link image preview anchor',
            'searchreplace visualblocks code',
            'insertdatetime media table paste code'
          ],
          toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
          content_css: [
            '//fast.fonts.net/cssapi/e6dc9b99-64fe-4292-ad98-6974f93cd2a2.css',
            '//www.tinymce.com/css/codepen.min.css'
          ]
        });
    
    </script>
    
    <aside id="projects" class="col-md-2 bs-docs-sidebar">
        <nav itemscope itemtype="http://schema.org/SiteNavigationElement">
            <ul class="list-group small" itemprop="project">
                    <li><a class="list-group-item" href="config.php?skills"><?= LABEL_SKILLS ?><span class="glyphicon glyphicon-pencil pull-right"></a></li>
                    <li><a class="list-group-item" href="config.php?users"><?= LABEL_CLASS_ROLL ?><span class="glyphicon glyphicon-pencil pull-right"></a></li>
                    <li><a class="list-group-item active" href="config.php?welcome"><?= LABEL_CONFIG_WELCOME ?><span class="glyphicon glyphicon-pencil pull-right"></a></li>
            </ul>
        </nav>
    </aside>

    <main class="col-md-10" id="content">
               
          
           <form action="config.php" method="post" id="form">
               <label><?= LABEL_CONFIG_WELCOME ?></label>
               <textarea name="message_board" class="form-control" rows="12"><?= $message ?></textarea>
                <p class="help-block">HTML tags: <code style="color:gray;"><?= htmlspecialchars(ALLOWED_HTML_TAGS) ?></code> <br />Custom: <code style="color:gray;">%user_name% %user_lastname%</code></p>
                <button type="submit" class="btn btn-sm btn-primary" style="margin-right:0.5em;" name="change_message_board" >
                <?= LABEL_SUBMIT ?></button>
           </form>
                 
    </main>
    
