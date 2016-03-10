<article>
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <header>
                    <div class="page-header">
                        <h1>update: <?php echo $header_url; ?></h1>
                    </div>
                </header>

                <?php if(!isset($data) || !array_key_exists('fields', $data)) { ?>
                    <p>No data found.</p>
                <?php } else if(array_key_exists('fields', $data)) {
                    $form_url = explode('/', $url);
                    unset($form_url[(count($form_url) - 1)]);
                    $form_url = implode('/', $form_url); ?>
                    <form action="<?php echo $form_url; ?>" method="post">
                        <?php if(isset($_GET['errors']) && strlen($_GET['errors']) > 0) {
                            $errors = explode(',', $_GET['errors']); ?>
                            <div class="form-group">
                                <div class="alert alert-danger">
                                    <strong>Errors:</strong>
                                    <?php echo $_GET['errors']; ?>
                                </div>
                            </div>
                        <?php } ?>
                        <?php foreach($form_fields as $key => $value) {
                            echo '<div class="form-group';
                            echo (isset($errors) && in_array($key, $errors)) ? ' has-error">' : '">';
                            echo $value;
                            echo '</div>';
                        } ?>
                        <div class="form-group">
                            <button class="btn btn-primary" type="submit">Submit</button>
                        </div>
                    </form>
                <?php } ?>

            </div>
        </div>
    </div>
</article>