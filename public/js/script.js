$(document).ready(function () 
{
    $('#togglePassword').on('click', function () 
    {
        const $password = $('#password');
        const type = $password.attr('type') === 'password' ? 'text' : 'password';
        $password.attr('type', type);
        const $icon = $(this).find('i');
        $icon.toggleClass('fa-eye fa-eye-slash');
    });
});