<!DOCTYPE html>

<html lang="<?= $lang['LANG'] ?>">

    <head>
        <meta charset="UTF-8">
        
        <link href="/css/bootstrap.min.css" rel="stylesheet"/>
        <link href="/css/bootstrap-theme.min.css" rel="stylesheet"/>
        <link href="/css/styles.css" rel="stylesheet"/>
        <link href="/css/sticky-footer.css" rel="stylesheet"/>

        <?php if (isset($title)): ?>
            <title>MediaGrade: <?= htmlspecialchars($title) ?></title>
        <?php else: ?>
            <title>MediaGrade</title>
        <?php endif ?>


        <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
        <script src="/js/validator.min.js"></script>
        <script src="/js/bootstrap.min.js"></script>

        
        <!-- http://underscorejs.org/ -->
        <script src="/js/underscore-min.js"></script>
        
        <!-- https://github.com/twitter/typeahead.js/ -->
        <script src="/js/typeahead.jquery.js"></script>
        <script src="/js/bloodhound.js"></script>
        <script src="/js/scripts.js"></script>        
    </head>

    <body>
      
<div class="container ">
    <?php if (isset($title)) if($title === "Log In"): ?>
    <div class="row text-center">
            
        <header class="col-md-12">
            <h1><a href="/"><img alt="MediaGrade" src="/img/logo.png"/></a></h1>
        </header>
   </div>  
   <?php endif ?>     
            
            
            
