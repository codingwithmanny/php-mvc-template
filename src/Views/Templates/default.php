<!DOCTYPE html>
<html>
<head>
    <title>Title of the document</title>

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

</body>
</html>