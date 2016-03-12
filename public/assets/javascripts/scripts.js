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
                options.data = JSON.stringify(payload);
                options.contentType = 'application/json; charset=utf-8';
            } else {
                options.data = payload;
            }
        }

        $.ajax(options);
    }

    //livesearch
    $('input.livesearch').each(function(){
        if($(this).attr('data-model') != null && $(this).attr('data-model') != '' && webtoken != '') {
            var id = $(this).attr('name');
            var model = $(this).attr('data-model');
            var dataid = $(this).attr('data-id');
            var datalabel = $(this).attr('data-label');
            //console.log(parent);

            //hide input
            $(this).attr('type', 'hidden');

            //add search
            $(this).parent().append('' +
                '<div class="dropdown">' +
                '<input class="form-control" placeholder="Search for ' + id + '" type="search" id="' + id + '" value="" />' +
                    '<ul id="' + id + '-menu" class="dropdown-menu" style="width: 100%;">' +
                    '</ul>' +
                '</div>');

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

            $('form').on('click', '#'+id+'-menu a', function(){
                var data = $(this).attr('data-value');
                var label = $(this).attr('data-label');
                $('#'+id).parent().append('<button class="btn btn-primary">'+label+' &times;</button>');
                $('#' + id).val('').hide();
                $('#' + id + '-menu').html('').hide();
                return false;
            });
        }
    });
});