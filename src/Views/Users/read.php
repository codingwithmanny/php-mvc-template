<article>
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <header>
                    <div class="page-header">
                        <h1>read: <?php
                            $url = '';
                            $header_url = '';
                            foreach($model_url as $key => $value) {
                                $header_url .= (($key+1) != count($model_url)) ? '<a href="/' . $value .'">' : '';
                                $header_url .= $value;
                                $header_url .= (($key+1) != count($model_url)) ? '</a>' : '';
                                $header_url .= ($key != 0) ? '' : '&nbsp;<small>/</small>&nbsp;';
                                $url .= $value;
                                $url .= ($key != 0) ? '' : '/';
                            }
                            echo $header_url;
                            ?></h1>
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
                <?php } ?>

            </div>
        </div>
    </div>
</article>


