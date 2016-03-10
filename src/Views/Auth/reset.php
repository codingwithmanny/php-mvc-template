<article>
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <header>
                    <div class="page-header">
                        <h1><?php echo $header_url; ?></h1>
                    </div>
                </header>
                <?php
                if(!array_key_exists('errors', $data)) {
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
                        <input type="hidden" name="token" value="<?php echo $data['token']; ?>">
                        <div class="form-group">
                            <input name="password" type="password" class="form-control" required="required" placeholder="New Password">
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary" type="submit">Submit</button>
                        </div>
                    </form>
                <?php } else {
                    foreach($data['errors'] as $key => $value) {
                        foreach($value as $k => $v) { ?>
                        <div class="alert alert-danger">
                            <strong><?php echo $k; ?>:</strong>
                            <?php echo $v; ?>
                        </div>
                    <?php }
                        }
                }
                /*var_dump($data);
                $form_url = explode('/', $url);
                unset($form_url[(count($form_url) - 1)]);
                $form_url = implode('/', $form_url); ?>
                <form action="<?php echo $form_url; ?>/forgotpassword" method="post">
                    <?php if(isset($_GET['success']) && strlen($_GET['success']) > 0) {
                        $errors = explode(',', $_GET['success']); ?>
                        <div class="form-group">
                            <div class="alert alert-success">
                                <strong>Success:</strong>
                                <?php echo $_GET['success']; ?>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if(isset($_GET['errors']) && strlen($_GET['errors']) > 0) {
                        $errors = explode(',', $_GET['errors']); ?>
                        <div class="form-group">
                            <div class="alert alert-danger">
                                <strong>Errors:</strong>
                                <?php echo $_GET['errors']; ?>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <input name="email" type="email" class="form-control" required="required" placeholder="Email">
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary" type="submit">Submit</button>
                    </div>
                </form>*/?>

            </div>
        </div>
    </div>
</article>


