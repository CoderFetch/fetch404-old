$(document).ready(function() {
    $('#loginBtn').on('click', function(e) {
        e.preventDefault();

        var name = $('#username').val();
        var password = $('#password').val();

        var ajax = $.ajax({
           type: 'POST',
           url: '/auth/login',
           dataType: 'json',
           data: {
               name_or_email: name,
               password: password
           }
        });

        ajax.success(function(data) {
           if (data.status) {
               if (data.status == 'success') {
                   window.location.href = '/';
               }
           } else {
                console.log(data);
           }
        });
    });

});

function switchToSignup() {
    $('#login').modal('hide');
    $('#register').modal('show');
}