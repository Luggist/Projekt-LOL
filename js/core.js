/*  there are other libraries that could use "$" - so it is 100% save to use "jQuery" instead of "$"
    if you don't use any other libraries than jQuery you could still go with "$"
    so the following line would be
    $(document).ready(function() {
*/
jQuery(document).ready(function() {

    var editModal = $('#editModal');

    editModal.on('show.bs.modal', function (event) {

        var button = $(event.relatedTarget) // Button that triggered the modal
        var addressId = button.data('id') // Extract info from data-* attributes

        var that = this;


        var theTitle = "Neue Adresse anlegen";
        var thePrimaryButton = "Hinzufügen";
        var apiRequestUrl = "api/address/?returnView=true";

        if(typeof addressId !== "undefined")
        {
            editModal.find('.id').html(addressId);
            theTitle = "Adresse mit der ID " + addressId + " bearbeiten";
            thePrimaryButton = "Speichern";

            apiRequestUrl = apiRequestUrl + "&id=" + addressId;
        }

        //this is to give the title and the "save" button different labels if they clicked on edit or new
        editModal.find('.modal-title').html(theTitle);
        editModal.find('.btn-primary').html(thePrimaryButton);

        //before we have a formular loaded via ajax - we don't want them to be able to click on "save"
        //therefore we disable the button
        editModal.find('.btn-primary').prop('disabled', true);

        jQuery.ajax({
            'url': apiRequestUrl,
            'method': 'get',
            'success': function(receivedData) {

                if(receivedData.result) {
                    var modal = $(that)
                    modal.find('.modal-body').html(receivedData.data.html);
                    editModal.find('.btn-primary').prop('disabled', false);
                } else { //there was an error - do something!

                }
            }
        });



    });


    editModal.find('.btn-primary').click(function() {
        editModal.find('form').trigger('submit', [this]);
    });


    $('.triggerDelete').click(function(e) {
        e.preventDefault();

        var r = confirm("Wollen Sie die Adresse wirklich löschen?");
        if (r == true) {
            var dataToSend = {'id':$(this).attr('data-id')};
            $.ajax({
                'url': $(this).attr('href'),
                'method': 'delete',
                'data': dataToSend,
                'dataType': "json",
                'success': function (receivedData) {
                    if(receivedData.result) {
                        window.setTimeout(function() {
                            location.reload();
                        }, 500);
                    } else {
                        //@TODO display error message
                    }
                }
            });
        }
    });

});