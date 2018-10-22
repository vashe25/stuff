/** File upload **/
$.ajax({
    url: form.action,
    data: new FormData(form),
    method: form.method,
    dataType: 'json',
    processData: false,
    contentType: false,
    success: function(res) {
        if (res.success) {
            $(form)[0].reset();
            $(form).find('.text-active').removeClass('text-active');
            $(form).find('.site-add-form__step2').addClass('wishes-box_active');
            $(form).on('click', '.site-add-form__step2 .btn', function(e) {
                e.preventDefault();
                $(form).find('.site-add-form__step2').removeClass('wishes-box_active');
            });
        }
    },
});