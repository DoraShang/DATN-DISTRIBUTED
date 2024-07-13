// function innit() {
//     var user = localStorage.getItem("user");
//     user = JSON.parse(user);
//     console.log(user);
//     if (user == undefined || user == '' || user == null) {
//         // window.location.href = './index.html';
//         $('.login-wrap').removeClass('hide');
//         $('.vehicle-main').addClass('hide');
//     } else {
//         $('.vehicle-main').removeClass('hide');
//         $('.login-wrap').addClass('hide');
//     }
// }
// innit()

// login-form

function login_handle() {
    var username = $('#login-form').find('.username').val();
    var password = $('#login-form').find('.password').val();

    if (username == '' || username == undefined) {
        alert('Vui lòng nhập thông tin đăng nhập')
        return false;
    }

    if (password == '' || password == undefined) {
        alert('Vui lòng nhập mật khẩu')
        return false;
    }

    var data = {
        'username': username,
        'password': password
    };
    console.log(data);
    startSending()
    $.ajax({
        url: API_LOGIN,
        data: { data },
        type: 'post',
        dataType: 'json',
        success: function (d) {
            console.log(d);
            if (d.res == 'done') {

            } else {

            }
            endSending();
        },
        error: function (err) {
            console.log(err);
            endSending();
        },
    })
}

// function login_handle() {
//     var password = $('#login-form').find('.username').val();
//     var username = $('#login-form').find('.password').val();

//     if (username == '' || username == undefined) {
//         alert('Vui lòng nhập thông tin đăng nhập')
//         return false;
//     }

//     if (password == '' || password == undefined) {
//         alert('Vui lòng nhập mật khẩu')
//         return false;
//     }

//     var data = {
//         'username': username,
//         'password': password,
//         'fullname': 'DoraShang',
//     };
//     localStorage.setItem("user", JSON.stringify(data));
//     // lưu vào local_store
//     // window.location.href = './index.html'
//     $('.vehicle-main').removeClass('hide');
//     $('.login-wrap').addClass('hide');


// }