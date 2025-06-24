jQuery(document).ready(function($) {

    // Helper to handle response (in case already JSON)
    function parseResponse(response) {
        if (typeof response === 'string') {
            try {
                return JSON.parse(response);
            } catch (e) {
                return { success: false, message: 'Invalid response from server.' };
            }
        }
        return response;
    }

    // Profile Picture
    $('#form-picture').on('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        formData.append('action', 'update_profile_picture');
        formData.append('nonce', ajax_obj.nonce);

        $.ajax({
            url: ajax_obj.ajax_url,
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                response = parseResponse(response);
                if (response.success) {
                    $('.jahbulonn-profile-picture').attr('src', response.file_url);
                    alert('Profile picture updated!');
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('Error updating profile picture.');
            }
        });
    });

    // Display Name
    $('#form-username').on('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        formData.append('action', 'update_display_name');
        formData.append('nonce', ajax_obj.nonce);

        $.ajax({
            url: ajax_obj.ajax_url,
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                response = parseResponse(response);
                if (response.success) {
                    alert('Display name updated!');
                    $('.jahbulonn-profile-info strong').text("I'm " + $('#display_name').val());
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('Error updating name.');
            }
        });
    });

    // Password
    $('#form-password').on('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        formData.append('action', 'update_password');
        formData.append('nonce', ajax_obj.nonce);

        $.ajax({
            url: ajax_obj.ajax_url,
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                response = parseResponse(response);
                if (response.success) {
                    alert('Password updated!');
                    $('#password').val('');
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('Error updating password.');
            }
        });
    });

});
