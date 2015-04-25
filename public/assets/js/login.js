$(document).ready(function() {
    $('#loginBtn').on('click', function(e) {
        e.preventDefault();

        var name = $('#username').val();
        var password = $('#password').val();

        $.ajax({
            type: 'post',
            url: '/json/auth/login',
            data: {
                name_or_email: name,
                password: password
            },
            dataType: 'json'
        }).success(function(data) {

        }).fail(function(data) {
            var errors = data.responseText;
            console.log(errors);
        });
    });
});

function switchToSignup() {
    $('#login').modal('hide');
    $('#register').modal('show');
}