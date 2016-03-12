$(document).ready(function(){
    console.log('jQuery Loaded!');

    /**
     *
     * @param str
     * @returns {boolean}
     */
    function validate_json(str)
    {
        try {
            JSON.parse(str);
        } catch (e) {
            return false;
        }
        return true;
    }

    /**
     *
     * @param url
     * @param method
     * @param payload
     * @param callback
     * @param failback
     */
    function ajax_request(method, url, payload, callback, failback) {
        var options = {
            type        : method,
            url         : url,
            dataType    : 'json',
            processData : false,
            cache       : false,
            contentType : 'application/json',
            beforeSend  : function(xhr) {
                xhr.setRequestHeader('Accept', 'application/json');
                xhr.setRequestHeader('WEBTOKEN', webtoken);
            },
            error       : function(jq_xhr, text_status) {
                if(failback) {
                    failback(jq_xhr, text_status);
                }
            },
            success     : function(data, text_status, jq_xhr) {
                if(callback) {
                    callback(data, text_status, jq_xhr);
                }
            }
        };

        if(payload !== null && payload !== undefined && payload !== false && payload !== '') {
            if (validate_json(payload)) {
                console.log('valid json');
                options.data = payload;
                options.contentType = 'application/json; charset=utf-8';
            } else {
                console.log('NOT json');
                options.data = payload;
                options.contentType = false;
                options.dataType = false;
            }
        }
        console.log(options);

        $.ajax(options);
    }

    //livesearch
    $('input.livesearch').each(function(){
        if($(this).attr('data-model') != null && $(this).attr('data-model') != '' && webtoken != '') {
            var id = $(this).attr('name');
            var model = $(this).attr('data-model');
            var dataid = $(this).attr('data-id');
            var datalabel = $(this).attr('data-label');
            var value = $(this).val();

            //hide input
            $(this).attr('type', 'hidden');

            var dropdown = '<div class="dropdown"><input autocomplete="off" class="form-control" placeholder="Search for ' + id + '" type="search" id="' + id + '" value=""';

            if(value != null && value != '') {
                dropdown += 'style="display: none;"';
            }

            dropdown += ' /><ul id="' + id + '-menu" class="dropdown-menu" style="width: 100%;"></ul></div>';

            //add search
            $(this).parent().append(dropdown);

            //on keyup
            $('#'+id).on('keyup', function(event){
                var value = $(this).val();
                $('#' + id + '-menu').html('');
                if(value.length > 1) {
                    $('#' + id + '-menu').append('<li class="text-center">Searching...</li>').show();
                    ajax_request('get', '/'+model+'?q='+value+'&limit=5', null, function(data, text_status, jq_xhr) {
                        if(data.data.length > 1) {
                            $('#' + id + '-menu').html('');
                            var li;
                            for(var i = 0; i < data.data.length; i++) {
                                li = '<li><a href="#" ';
                                for(var x in data.data[i]) {
                                    if(x == dataid) {
                                        li += 'data-value="' + data.data[i][x] + '" ';
                                    }
                                    if(x == datalabel) {
                                        li += 'data-label="' + data.data[i][x] + '" ';
                                    }
                                }
                                li += '>';
                                for(var x in data.data[i]) {
                                    li += x + ': ' + data.data[i][x] + ' ';
                                }
                                li += '</a></li>';
                                $('#' + id + '-menu').append(li);
                            }
                        } else {
                            $('#' + id + '-menu').html('').append('<li class="text-center">No results</li>').show();
                        }
                    });
                } else {
                    $('#'+id+'-menu').hide();
                }
            });

            //when dropdown link click
            $('form').on('click', '#'+id+'-menu a', function(){
                var data = $(this).attr('data-value');
                var label = $(this).attr('data-label');
                $('input[name='+id+']').val(data);

                var button = $('<button class="btn btn-primary">'+label+' &times;</button>');
                button.click(function(){
                    $('input[name='+id+']').val('');
                    $('#' + id).show();
                    button.remove();
                    return false;
                });
                $('#' + id).parent().append(button);
                $('#' + id).val('').hide();
                $('#' + id + '-menu').html('').hide();
                return false;
            });

            //get existing value
            if(value != null && value != '') {
                ajax_request('get', '/'+model+'/'+value, null, function(data, text_status, jq_xhr) {
                    if(data.data) {
                        if(datalabel in data.data) {
                            var button = $('<button class="btn btn-primary">'+data.data[datalabel]+' &times;</button>');
                            button.click(function(){
                                $('input[name='+id+']').val('');
                                $('#' + id).show();
                                button.remove();
                                return false;
                            });
                            $('#' + id).parent().append(button);
                        }
                    }
                });
            }
        }
    });

    //file upload
    $('input.file').each(function() {
        if($(this).attr('data-accept') != null && $(this).attr('data-accept') != '' && webtoken != '') {
            var accept = $(this).attr('data-accept');
            var id = $(this).attr('name');
            var model = $(this).attr('data-model');
            var field = $(this);

            //hide input
            $(this).attr('type', 'hidden');

            var upload_container = $('<div id="' + id +'-uploader" style="display: block; overflow: hidden;"></div>');
            var upload_input = $('<input id="' + id + '-file" type="file" class="form-control" style="display: none;" />');
            var upload_button = $('<button class="btn btn-primary" data-loading-text="Uploading...">Choose file</button>');

            upload_input.on('change', function(){
                var input = $(this).val();
                if(input != '' && input != null) {
                    var form_data = new FormData();
                    var file_data = document.getElementById(id + '-file');
                    var file;
                    for(var i = 0; i < file_data.files.length; i++) {
                        file = file_data.files[i];
                        form_data.append('file', file);
                    }
                    upload_button.button('loading');
                    $('input').attr('disabled', 'disabled').addClass('disabled');
                    $('button[type=submit]').attr('disabled', 'disabled').addClass('disabled');
                    ajax_request('post', '/' + model + '/upload', form_data, function(data){
                        if(data.data != false) {
                            upload_button.button('reset').hide();
                            field.val(data.data);
                            upload_container.append('<div class="thumbnail col-xs-1"><img src="/tmp/'+data.data+'"/><a href="#" data-value="'+data.data+'" class="btn btn-danger btn-block">&times;</a></div>');
                        }
                        $('input').removeAttr('disabled').removeClass('disabled');
                        $('button[type=submit]').removeAttr('disabled').removeClass('disabled');
                    }, function() {
                        $('input').removeAttr('disabled').removeClass('disabled');
                        $('button[type=submit]').removeAttr('disabled').removeClass('disabled');
                    });
                }
            });

            upload_button.click(function(){
                $(this).parent().children('input').click();
                return false;
            });

            upload_container.on('click', '.thumbnail a', function(){
                var file = JSON.stringify({file: $(this).attr('data-value')});
                $('input').attr('disabled', 'disabled').addClass('disabled');
                $('button[type=submit]').attr('disabled', 'disabled').addClass('disabled');
                ajax_request('post', '/' + model + '/deleteupload', file, function(data) {
                    if(data.data == true) {
                        field.val('');
                        upload_container.children('.thumbnail').remove();
                        upload_button.button('reset').show();
                    }
                    $('input').removeAttr('disabled').removeClass('disabled');
                    $('button[type=submit]').removeAttr('disabled').removeClass('disabled');
                }, function(){
                    $('input').removeAttr('disabled').removeClass('disabled');
                    $('button[type=submit]').removeAttr('disabled').removeClass('disabled');
                });
                return false;
            });

            upload_container.append(upload_input);
            upload_container.append(upload_button);

            //add uploader
            $(this).parent().append(upload_container);
        }
    });
});