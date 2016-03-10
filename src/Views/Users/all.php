<article>
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <header>
                    <div class="page-header">
                        <a href="<?php echo $url; ?>/create" class="btn btn-primary pull-right">Create</a>
                        <h1>all: <?php echo $header_url; ?></h1>
                    </div>
                </header>

                <?php
                $columns = (array_key_exists('data', $data) && count($data['data']) > 0) ? $data['data'] : null;
                if($columns == null) { ?>
                    <p class="text-center">No Data</p>
                <?php } else if($columns != null) {
                ?>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <?php
                                foreach($columns[0] as $key => $value) {
                                    echo '<th>' . $key . '</th>';
                                }
                                ?>
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if($columns != null) {
                                foreach($columns as $key => $value) {
                                    echo '<tr>';
                                        foreach($value as $k => $v) {
                                            echo '<td>';
                                            echo $v;
                                            echo '</td>';
                                        }
                                        echo '<td><a href="' . $url . '/' . $value['id'] . '">View</a></td>';
                                        echo '<td><a href="' . $url . '/' . $value['id'] . '/delete">Delete</a></td>';
                                    echo '</tr>';
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <?php } ?>

                <hr>

                <?php $pagination = $data['pagination']; ?>
                    <p class="text-center"><small><?php
                        foreach($pagination as $key => $value) {
                            echo '&nbsp;<strong>' . ucwords($key) . '</strong>&nbsp;';
                            echo $value . '&nbsp;';
                        }
                            ?></small></p>
                <nav>
                    <ul class="pager">
                        <li class="previous <?php echo ($pagination['has_prev']) ? '' : 'disabled'; ?>"><a href="<?php echo $pagination['url_prev']; ?>"><span aria-hidden="true">&larr;</span> Older</a></li>
                        <li class="next <?php echo ($pagination['has_next']) ? '' : 'disabled'; ?>"><a href="<?php echo $pagination['url_next']; ?>">Newer <span aria-hidden="true">&rarr;</span></a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</article>


