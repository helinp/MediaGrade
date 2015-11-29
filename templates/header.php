<!DOCTYPE html>

<html lang="<?= LANG ?>">

    <head>
        <meta charset="UTF-8">
        
        <link href="/css/bootstrap.css" rel="stylesheet"/>
    <!--    <link href="/css/bootstrap-theme.min.css" rel="stylesheet"/>-->
        <link href="/css/styles.css" rel="stylesheet"/>
        <link href="/css/sticky-footer.css" rel="stylesheet"/>
        <link rel="stylesheet"  type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.min.css" />
        <link rel="stylesheet"  type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" />


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
      

    <?php if(in_array($_SERVER["PHP_SELF"], ["/login.php", "/register.php", "/logout.php", "/forgot.php"])): ?>
<div class="container" style="min-height:0;-webkit-border-radius: 7px;-moz-border-radius: 7px;border-radius: 7px;">
    <div class="row text-center">
            
        <header class="col-md-12">
            <h1><a href="/"><img alt="MediaGrade" src="/img/logo.png"/></a></h1>
        </header>
   </div>  
   <?php else: ?>     
<div class="container ">            
   <?php endif ?>           
            
