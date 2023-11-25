function signup() {
    var username = document.getElementById("signup-username").value;
    var fullname = document.getElementById("signup-fullname").value;
    var password = document.getElementById("signup-password").value;
    var phone = document.getElementById("signup-phone").value;

    $.ajax({
        type: "POST",
        url: "signup.php",
        data: {
            username: username,
            fullname: fullname,
            password: password,
            phone:phone
        },
        success: function(response) {
            $("#result").html(response);
        }
    });
}
