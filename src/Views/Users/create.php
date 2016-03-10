<article>
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <header>
                    <div class="page-header">
                        <h1>create: <?php echo $header_url; ?></h1>
                    </div>
                </header>

                <?php if(!isset($data) || !array_key_exists('fields', $data)) { ?>
                    <p>No data found.</p>
                <?php } else if(array_key_exists('fields', $data)) {
                    $form_url = explode('/', $url);
                    unset($form_url[(count($form_url) - 1)]);
                    $form_url = '/' . implode('/', $form_url); ?>
                    <form action="<?php echo $form_url; ?>" method="post">
                        <?php foreach($form_fields as $key => $value) {
                            echo '<div class="form-group">';
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


