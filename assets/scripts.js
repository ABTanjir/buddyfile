jQuery(function($){
    "use strict"
    
    var submit_url = $('#filebuddy_uploader').data('upload');
    
    $('.file_upload').uploader({
        upload_url: submit_url,
        input_name: 'file',
        auto_upload: true,
        maximum_total_files: 4,
        maximum_file_size: 200 * 1024 * 1024,
        minimum_file_size: 20 * 1024 * 1024,
        nonce: uploader.nonce,
        file_types_allowed: ['video/mp4', 'video/3gp', 'video/ogg', 'video/mov'],
        on_before_upload: function(el) {
            $(el).prop('disabled', true);
            var status_dom = $(el).closest('div').find('.uploader_status');
            if(status_dom.length){
                $(status_dom).text('Processing...');
            }else{
                $(el).closest('div').append('<p class="uploader_status">Processing...</>')
            }
        },
        on_success_upload: function(el) {
            $(el).prop('disabled', false);
            var status_dom = $(el).closest('div').find('.uploader_status');
            if(status_dom.length){
                $(status_dom).text('Uploaded');
            }else{
                $(el).closest('div').append('<p class="uploader_status">Uploaded</>')
            }
        },
        on_finish_upload: function(e) {

        },
        on_error: function(err, el) {
            var message;
            var err_dom = $(el).closest('div').find('.uploader-error');

            if(err.status == "error_maximum_file_size"){
                var message = 'File is too large';
            }else if(err.status == "error_minimum_file_size"){
                var message = 'File is too small';
            }

            if($(err_dom).length){
                $(err_dom).html(message);
            }else{
                $(el).closest('div').append('<p class="uploader-error">'+message+'</>');
            }

        }
    })





});