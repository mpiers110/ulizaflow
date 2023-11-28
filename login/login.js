function showPass(){
    $('#show').toggleClass('fas fa-eye fas fa-eye-slash');
    $('#password').attr('type', function(index, attr){
        return attr === 'password' ? 'text' : 'password';
    });
}
function login(){
    let username = $('#username').val();
    let password = $('#password').val();
    $.ajax({
        url: document.location.origin+'/ulizaflow/backend/auth/login/?login',
        type: "POST",
        data: {
            username: username,
            password: password,         
        },
        success: function(){
            window.location.href = document.location.origin+'/ulizaflow/account/';
        },
        error: function(error){
            console.error(error)
        }
    });
}