<!DOCTYPE html>
<html>
<head>
    <title>Title of the document</title>
</head>
<body>
    <h1>DEFAULT</h1>

    <?php
    $file = APP .'Views/' . $template . '.php';
    if(file_exists($file)) {
        require($file);
    } ?>

</body>
</html>