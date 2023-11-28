function validatePassword(){
    const pass = $('#pass1').val();
    const confirm = $('#pass2').val();
    if (pass!=confirm){
        $('#pass2').addClass('is-invalid');
        $('#password_status').html("The passwords do not match");
        $('#password-break').show()
        $('#send').prop('disabled', true);
    }else{
        $('#pass2').removeClass('is-invalid');
        $('#pass2').addClass('is-valid');
        $('#password_status').html("");
        $('#password-break').hide()
        $('#send').prop('disabled', false);
    }
}
function passwordChecker(){
    let number = /([0-9])/;
    let alphas = /([a-zA-Z])/;
    let specialChars = /([~,!,@,#,$,%,^,&,*,-,_,+,=,?,>,<])/;
    let pass = $('#pass1').val().trim();
    if (pass.length < 8){
        $('#pass1').removeClass('is-valid');
        $('#pass1').addClass('is-invalid');
        $('#password_status').html("Should be atleast 8 characters.");
        $('#password-break').show()
        $('#send').prop('disabled', true);
    }else{
        if (pass.match(number) && pass.match(alphas) && pass.match(specialChars)) {
            $('#pass1').removeClass('is-invalid');
            $('#pass1').addClass('is-valid');
            $('#password_status').html("");
            $('#password-break').hide()
        }else{
            $('#pass1').removeClass('is-valid');
            $('#pass1').addClass('is-invalid');
            $('#password_status').html("Should contain letters, numbers and special characters.");
            $('#password-break').show()
        }
    }
}
function checkUser(element){
    let keyword = $(element).val();
    let id =  $(element).attr('id')
    $.ajax({
        url: document.location.origin+'/ulizaflow/backend/auth/register/?checkuser='+id,
        type: "POST",
        data: {
            keyword:keyword,         
        },
        success: function(response){
            if(response =="OK"){
                if (id == 'username' && keyword != ""){
                    $(element).removeClass('is-invalid');
                    $(element).addClass('is-valid');
                    $('#username_status').html("");
                    $('#username-break').hide()
                }else{
                    $(element).removeClass('is-invalid');
                    $(element).addClass('is-valid');
                    $('#email_status').html("");
                    $('#email-break').hide()
                }
                $('#next').prop('disabled', false);
                $('#send').prop('disabled', false);
            }else{
                if (id == 'username' && keyword != ""){
                    $(element).removeClass('is-valid');
                    $(element).addClass('is-invalid');
                    $('#username_status').html("Username not available");
                    $('#username-break').show()
                    $('#send').prop('disabled', true);
                }else{
                    $(element).removeClass('is-valid');
                    $(element).addClass('is-invalid');
                    $('#email_status').html("This email cannot be used");
                    $('#email-break').show()
                    $('#next').prop('disabled', true);
                }
            }
        }
    });
}
function register(){
    let dob = $('#dob').val();
    let email = $('#email').val();
    let username = $('#username').val();
    let password = $('#pass1').val();
    $.ajax({
        url: document.location.origin+'/ulizaflow/backend/auth/register/?registeruser',
        type: "POST",
        data: {
            dob:dob,
            email: email,
            username: username,
            password: password,         
        },
        success: function(response){
            if (response === 'Account registration successfull'){
                window.location.href = document.location.origin+'/ulizaflow/account/';
            }else{
                alert(response);
            }
        },
        error: function(error){
            console.error(error)
        }
    });
}
