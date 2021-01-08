$(document).ready(function() {
    $('.nav-tabs .nav-item .nav-link').removeAttr('href data-toggle role aria-labelledby');

    $('#btn_service').click(function() {

        var error_webservice_link = '';
        var error_webservice_username = '';
        var error_webservice_password = '';
        var url_filter = /((([A-Za-z]{3,9}:(?:\/\/)?)(?:[-;:&=\+\$,\w]+@)?[A-Za-z0-9.-]+|(?:www.|[-;:&=\+\$,\w]+@)[A-Za-z0-9.-]+)((?:\/[\+~%\/.\w-_]*)?\??(?:[-\+=&;%@.\w_]*)#?(?:[\w]*))?)/;

        if($.trim($('#webservice-link').val()).length == 0) {
            error_webservice_link = 'Web Service Link is required';
            $('#error_webservice_link').text(error_webservice_link);
            $('#webservice-link').addClass('has-error');
        } else {
            if (!url_filter.test($('#webservice-link').val())) {
                error_webservice_link = 'Invalid Link';
                $('#error_webservice_link').text(error_webservice_link);
                $('#webservice-link').addClass('has-error');
            } else {
                error_webservice_link = '';
                $('#error_webservice_link').text(error_webservice_link);
                $('#webservice-link').removeClass('has-error');
            }
        }

        if($.trim($('#webservice-username-input').val()).length == 0) {
            error_webservice_username = 'Username is required';
            $('#error_webservice_username').text(error_webservice_username);
            $('#webservice-username-input').addClass('has-error');
        } else {
            error_webservice_username = '';
            $('#error_webservice_username').text(error_webservice_username);
            $('#webservice-username-input').removeClass('has-error');
        }

        if($.trim($('#webservice-password-input').val()).length == 0) {
            error_webservice_password = 'Password is required';
            $('#error_webservice_password').text(error_webservice_password);
            $('#webservice-password-input').addClass('has-error');
        } else {
            error_webservice_password = '';
            $('#error_webservice_password').text(error_webservice_password);
            $('#webservice-password-input').removeClass('has-error');
        }

        if(error_webservice_link != '' || error_webservice_username != '' || error_webservice_password != '') {
            return false;
        } else {
            $('#webservice-tab').removeClass('active active_tab1');
            $('#webservice-tab').removeAttr('href data-toggle');
            $('#webservice, .step1').removeClass('active');
            $('#webservice-tab').addClass('inactive_tab1');
            $('#login-tab').removeClass('inactive_tab1');
            $('#login-tab').addClass('active_tab1 active');
            $('#login-tab').attr('href', '#login');
            $('#login-tab').attr('data-toggle', 'tab');
            $('#login, .step2').addClass('active show');
        }
    });

    $('#previous_btn_login').click(function() {
        $('#login-tab').removeClass('active active_tab1');
        $('#login-tab').removeAttr('href data-toggle');
        $('#login, .step2').removeClass('active show');
        $('#login-tab').addClass('inactive_tab1');
        $('#webservice-tab').removeClass('inactive_tab1');
        $('#webservice-tab').addClass('active_tab1 active');
        $('#webservice-tab').attr('href', '#webservice');
        $('#webservice-tab').attr('data-toggle', 'tab');
        $('#webservice, .step1').addClass('active show');
    });

    $('#btn_login').click(function() {
        var error_email = '';
        var error_password = '';
        var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

        if($.trim($('#email-input').val()).length == 0) {
            error_email = 'Email is required';
            $('#error_email').text(error_email);
            $('#email-input').addClass('has-error');
        } else {
            if (!filter.test($('#email-input').val())) {
                error_email = 'Invalid Email';
                $('#error_email').text(error_email);
                $('#email-input').addClass('has-error');
            } else {
                error_email = '';
                $('#error_email').text(error_email);
                $('#email-input').removeClass('has-error');
            }
        }

        if($.trim($('#password-input').val()).length == 0) {
            error_password = 'Password is required';
            $('#error_password').text(error_password);
            $('#password-input').addClass('has-error');
        } else {
            error_password = '';
            $('#error_password').text(error_password);
            $('#password-input').removeClass('has-error');
        }

        if($.trim($('#password-repeat-input').val()).length == 0) {
            error_password = 'Repeat Password is required';
            $('#error_password_repeat').text(error_password);
            $('#password-repeat-input').addClass('has-error');
        } else {
            if($.trim($('#password-input').val()) != $.trim($('#password-repeat-input').val())) {
                error_password = 'Repeat Password does not match';
                $('#error_password_repeat').text(error_password);
                $('#password-repeat-input').addClass('has-error');
            } else {
                error_password = '';
                $('#error_password_repeat').text(error_password);
                $('#password-repeat-input').removeClass('has-error');
            }
        }

        if(error_email != '' || error_password != '') {
            return false;
        } else {
            $('#login-tab').removeClass('active active_tab1');
            $('#login-tab').removeAttr('href data-toggle');
            $('#login, .step2').removeClass('active');
            $('#login-tab').addClass('inactive_tab1');
            $('#personal-tab').removeClass('inactive_tab1');
            $('#personal-tab').addClass('active_tab1 active');
            $('#personal-tab').attr('href', '#personal');
            $('#personal-tab').attr('data-toggle', 'tab');
            $('#personal, .step3').addClass('active show');
        }
    });

    $('#previous_btn_personal').click(function() {
        $('#personal-tab').removeClass('active active_tab1');
        $('#personal-tab').removeAttr('href data-toggle');
        $('#personal, .step3').removeClass('active show');
        $('#personal-tab').addClass('inactive_tab1');
        $('#login-tab').removeClass('inactive_tab1');
        $('#login-tab').addClass('active_tab1 active');
        $('#login-tab').attr('href', '#login');
        $('#login-tab').attr('data-toggle', 'tab');
        $('#login, .step2').addClass('active show');
    });

    $('#btn_personal').click(function() {

        var error_first_name = '';
        var error_last_name = '';
        var error_address = '';
        var error_contact_num = '';
        var mobile_validation = /^\d{10}$/;

        if ($.trim($('#first-name-input').val()).length == 0) {
            error_first_name = 'First Name is required';
            $('#error_first_name').text(error_first_name);
            $('#first-name-input').addClass('has-error');
        } else {
            error_first_name = '';
            $('#error_first_name').text(error_first_name);
            $('#first-name-input').removeClass('has-error');
        }

        if ($.trim($('#last-name-input').val()).length == 0) {
            error_last_name = 'Last Name is required';
            $('#error_last_name').text(error_last_name);
            $('#last-name-input').addClass('has-error');
        } else {
            error_last_name = '';
            $('#error_last_name').text(error_last_name);
            $('#last-name-input').removeClass('has-error');
        }

        if ($.trim($('#address-input').val()).length == 0) {
            error_address = 'Address is required';
            $('#error_address').text(error_address);
            $('#address').addClass('has-error');
        } else {
            error_address = '';
            $('#error_address').text(error_address);
            $('#address').removeClass('has-error');
        }

        if ($.trim($('#contact-num-input').val()).length == 0) {
            error_contact_num = 'Mobile Number is required';
            $('#error_contact_num').text(error_contact_num);
            $('#contact-num-input').addClass('has-error');
        } else {
            if (!mobile_validation.test($('#contact-num-input').val())) {
                error_contact_num = 'Invalid Mobile Number';
                $('#error_contact_num').text(error_contact_num);
                $('#contact-num-input').addClass('has-error');
            } else {
                error_contact_num = '';
                $('#error_contact_num').text(error_contact_num);
                $('#contact-num-input').removeClass('has-error');
            }
        }

        if (error_first_name != '' || error_last_name != '' || error_address != '' || error_contact_num != '') {
            return false;
        } else {

            $('#personal-tab').removeClass('active active_tab1');
            $('#personal-tab').removeAttr('href data-toggle');
            $('#personal, .step3').removeClass('active');
            $('#personal-tab').addClass('inactive_tab1');
            $('#finish-tab').removeClass('inactive_tab1');
            $('#finish-tab').addClass('active_tab1 active');
            $('#finish-tab').attr('href', '#finish');
            $('#finish-tab').attr('data-toggle', 'tab');
            $('#finish, .step4').addClass('active show');

            $('#btn_personal').attr("disabled", "disabled");
            $(document).css('cursor', 'prgress');

            var form = $('#register_form');
            var url = form.attr('action');

            $.ajax({
                type: "POST",
                url: url,
                data: form.serialize(),
                success: function(data) {
                    $('#loader').empty().html('Register complete!');
                }
            });

        }

    });






});