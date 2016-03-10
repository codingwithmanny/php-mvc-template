<article>
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <header>
                    <div class="page-header">
                        <a href="<?php echo $url; ?>/edit" class="btn btn-primary pull-right">Edit</a>
                        <h1>read: <?php echo $header_url; ?></h1>
                    </div>
                </header>

                <?php if(!array_key_exists('data', $data)) { ?>
                    <p>No data found.</p>
                <?php } else if(array_key_exists('data', $data)) {?>
                <div class="table-responsive">
                    <table class="table">
                        <tbody>
                        <?php
                        foreach($data['data'] as $key => $value) { ?>
                            <tr>
                                <th><?php echo $key; ?></th>
                                <td><?php echo $value; ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
                <?php }
                if(array_key_exists('related', $data)) {?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Model</th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($data['related'] as $key => $value) { ?>
                                <tr>
                                    <td><?php echo $value; ?></td>
                                    <td><a href="<?php echo $url . '/' . $value; ?>">View</a></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</article>


