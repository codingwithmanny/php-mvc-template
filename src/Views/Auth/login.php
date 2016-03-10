<article>
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <header>
                    <div class="page-header">
                        <h1><?php echo $header_url; ?></h1>
                    </div>
                </header>
                    <?php $form_url = explode('/', $url);
                    unset($form_url[(count($form_url) - 1)]);
                    $form_url = implode('/', $form_url); ?>
                    <form action="<?php echo $form_url; ?>/login" method="post">
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
                            <input name="password" type="password" class="form-control" required="required" placeholder="Password">
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary" type="submit">Submit</button>
                        </div>
                    </form>

            </div>
        </div>
    </div>
</article>


