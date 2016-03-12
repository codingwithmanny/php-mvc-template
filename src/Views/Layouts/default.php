<!DOCTYPE html>
<html>
<head>
    <title><?php echo $model_url; ?></title>

    <link rel="stylesheet" href="/assets/stylesheets/styles.css">
</head>
<body>
    <header>
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12">
                    <h1>Template: default.php</h1>
                </div>
            </div>
        </div>
    </header>

    <?php
    $file = APP .'Views/' . $template . '.php';
    if(file_exists($file)) {
        require($file);
    } ?>

    <script><?php require(ROOT . '/bower_components/jquery/dist/jquery.min.js'); ?></script>
    <script><?php require(ROOT . '/bower_components/bootstrap-sass/assets/javascripts/bootstrap.min.js'); ?></script>
    <script>
        <?php
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }?>
        var webtoken = '<?php echo (isset($_SESSION) && array_key_exists('WEBTOKEN', $_SESSION)) ? $_SESSION['WEBTOKEN'] : ''; ?>';
    </script>
    <script src="/assets/javascripts/scripts.js"></script>
</body>
</html>