jQuery(function($){
    "use strict"
    
    var submit_url = $('#filebuddy_uploader').data('upload');
    // console.log(200 * 1024 * 1024);
    $('.file_upload').uploader({
        upload_url: submit_url,
        input_name: 'file',
        auto_upload: true,
        maximum_total_files: 4,
        maximum_file_size: 200 * 1024 * 1024,
        minimum_file_size: 20 * 1024 * 1024,
        file_types_allowed: ['video/mp4', 'video/3gp', 'video/ogg', 'video/mov'],
        on_before_upload: function(e) {
            $(e).closest('form').find(":submit").prop('disabled', true);
            // $('.save-manual').attr('disabled', true)
        },
        on_success_upload: function(e) {
            // $(e).closest('form').find(":submit").prop('disabled', false);
            // $('.save-manual').attr('disabled', false)
        },
        on_finish_upload: function(e) {
            $(e).closest('form').find(":submit").prop('disabled', false);
        },
        on_error: function(err, el) {
            var message;
            if(err.status == "error_maximum_file_size"){
                var message = 'File is too large';
            }else if(err.status == "error_minimum_file_size"){
                var message = 'File is too small';
            }
            var err_dom = $(el).closest('div').find('.uploader-error');
            if($(err_dom).length){
                $(err_dom).html(message);
            }else{
                $(el).closest('div').append('<p class="uploader-error">'+message+'</>');
            }

        }
    })
});