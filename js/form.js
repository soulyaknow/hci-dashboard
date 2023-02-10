if($('#btn-signin').click(function(){
    checkIsEmpty();
}));

if($('#btn-signup').click(function(){
    checkEmpty();
}));

var checkEmpty=()=>
{
    if($('#username').val() != "" && $('#password').val() != "" && $('cpassword').val() != ""){
        doRegister();
    }else{
        swal("Error!", "Fill in the empty fields!", "error");
    }
}

var checkIsEmpty=()=>
{
    if($('#user_email').val() != "" && $('#user_password').val() != ""){
        doLogin();
    }else{
        swal("Error!", "Fill in the empty fields!", "error");
    }
}

var doRegister=()=>
{
    $.ajax({
        type: "POST",
        url: "include/router.php",
        data: {choice: 'register', username:$('#username').val(),email:$('#email').val(),password:$('#password').val(),cpassword:$('#cpassword').val()},
        success: function(data)
        {
            if(data == 200)
            {
                swal("Good job!", "Successfully Register!", "success");
                setTimeout(()=>{location.replace("index.html");}, 2500);
            }
            else
            {   
                swal("Warning!", "Email is already been used!", "warning");
            }
        },
        error: function(error)
        {
            console.log(error);
        }
    })
}

let login_attemps = 3;
var doLogin=()=>
{
    $.ajax({
        type: "POST",
        url: "include/router.php",
        data: {choice: 'login', user_email:$('#user_email').val(),user_password:$('#user_password').val()},
        success: function(data)
        {
            if(data == 1)
            {
                swal("Good job!", "Login Successfully!", "success");
                setTimeout(()=>{location.replace("dashboard.html");}, 2500);
            }
            else if(data == 2)
            {
                swal("Good job!", "Login Successfully!", "success");
                setTimeout(()=>{location.replace("afterIndex.html");}, 2500);
            }
            else if(data == 3)
            {
                swal("Error!", "Account is disabled!", "error");
            }
            else
            {
                if(login_attemps == 0)
                {
                    let attempt = 1;
                    let disable = 1;
                    swal("Error!", "No More Login Attempt Reload the page to try!", "error");
                    isLocked(attempt,disable); 
                }
                else
                {
                    login_attemps -= 1;
                    swal("Warning!", "Incorrect Username and Password!", "warning");
                    if(login_attemps == 0)
                    {
                        document.getElementById("user_email").disabled = true;
                        document.getElementById("user_password").disabled = true;
                    }
                }
            }
        },
        error: function(error)
        {
            console.log(error);
        }
    })
}
