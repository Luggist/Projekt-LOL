/*  there are other libraries that could use "$" - so it is 100% save to use "jQuery" instead of "$"
 if you don't use any other libraries than jQuery you could still go with "$"
 so the following line would be
 $(document).ready(function() {
 */
jQuery(document).ready(function() {

    var registerModal = $('#registerModal');

    $('.registerOverlay').click(function(e) {
        e.preventDefault();

        registerModal.modal('show');
    });


    //this is that we are able to trigger a submit although a button was clicked outside of your form!
    registerModal.find('.btn-primary').click(function() {
        registerModal.find('form').trigger('submit', [this]);
    });

    //so we have some input fields
    registerModal.find('form').bind('submit', function(e, that) {
        e.preventDefault();

        registerModal.find('.btn-primary').prop('disabled', true); //prevent sending the formular again while we check it

        hasError = false; //we are positive...

        if(typeof that === 'undefined') {
            that = registerModal.find('.btn-primary').get(0);
        }

        var nonEmptyFields = ['#name', '#pwd', '#pwd2'];

        for(i = 0; i < nonEmptyFields.length; i++) {
            if($(nonEmptyFields[i]).val() == '') {
                hasError = true;
                $(nonEmptyFields[i]).closest('.form-group').addClass('has-error');
            }
        }

        if(!hasError) {
            //check if pwd is long enough...
            if($('#pwd').val().length < 8) {
                $('#pwd').closest('.form-group').addClass('has-error');
                hasError = true;
            }
            else {

                if($('#pwd').val() != $('#pwd2').val()) {
                    $('#pwd2').closest('.form-group').addClass('has-error');
                    hasError = true;
                    registerModal.find('.btn-primary').prop('disabled', false);
                } else {
                    //everything fine

                    $.ajax({
                        'url': $(this).attr('action'),
                        'method': $(this).attr('method'),
                        'data': $(this).serialize(),
                        'dataType': "json",
                        'success': function (receivedData) {

                            if(receivedData.result)
                            {
                                toastr.success(receivedData.message);

                                window.setTimeout(function() {
                                    location.reload();
                                }, 2500);

                            }
                            else
                            {
                                registerModal.find('.form-group').removeClass('has-error');

                                $.each(receivedData.data.errorFields, function(key, value) {
                                    $('#'+key).closest('.form-group').addClass('has-error');
                                });
                            }

                            registerModal.find('.btn-primary').prop('disabled', false);
                        }
                    });

                }
            }
        }

        registerModal.find('.btn-primary').prop('disabled', false);


    });



});