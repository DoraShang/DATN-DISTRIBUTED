var VEHICLE = [];
var IP_CURRENT = '';
const socket = io(SOCKET);
var is_conect = false;
var CONNECT_SOCKET = false;

var check_request = true;

function innit_data() {
    // load VEHICLE
    var v = localStorage.getItem("vehicle");

    if (v != undefined && v != '' && v != null) {
        VEHICLE = JSON.parse(v);
        render_vehicle();
    }
    if (IP_CURRENT == '') {
        $('.tb-control-wrap').addClass('hide');
        $('.tb-wrap').removeClass('hide');
    } else {
        $('.tb-wrap').addClass('hide');
        $('.tb-control-wrap').removeClass('hide');
    }

    innit_socket();
}
innit_data();

function render_vehicle() {
    var html = '';
    var i = 0;
    VEHICLE.forEach(e => {
        i++;
        html += `<li class="tb-item">
        <div>
            <p>Xe ${i}</p>
            <p>IP: ${e}</p>
        </div>
        <button class="btn-gepnoi" onclick="send_ip('${e}')">
            Ghép nối
        </button>
    </li>`;
    })

    $('#tb_list').html(html);
}
function modal_add_vehicle() {
    $('#modal_add_tb').modal('show');
}

function add_vehicle() {
    var ip = $('#modal_add_tb .tb-ip').val();
    if (ip == undefined || ip == '') {
        alert('Vui lòng nhập IP thiết bị');
        return false;
    }
    if (VEHICLE.includes(ip)) {
        alert('Thiết bị đã tồn tại');
        return false;
    } else {
        VEHICLE.push(ip);
        localStorage.setItem("vehicle", JSON.stringify(VEHICLE));
    }
    render_vehicle();
    $('#modal_add_tb .btn-modal-close').trigger('click');
}

// decision
function to_left() {
    var command = 'decision:Left';
    data = {
        'data': command,
        'room_name': IP_CURRENT,
    }
    data = JSON.stringify(data)
    socket.emit('vehicle command', data);
}
function to_right() {
    var command = 'decision:Right';
    data = {
        'data': command,
        'room_name': IP_CURRENT,
    }
    data = JSON.stringify(data)
    socket.emit('vehicle command', data);
}
function to_down() {
    var command = 'decision:x';
    data = {
        'data': command,
        'room_name': IP_CURRENT,
    }
    data = JSON.stringify(data)
    socket.emit('vehicle command', data);
}
function to_up() {
    var command = 'decision:Up';
    data = {
        'data': command,
        'room_name': IP_CURRENT,
    }
    data = JSON.stringify(data)
    socket.emit('vehicle command', data);
}
function stop() {
    var command = 'decision:s';
    data = {
        'data': command,
        'room_name': IP_CURRENT,
    }
    data = JSON.stringify(data)
    socket.emit('vehicle command', data);
}

function innit_socket() {
    socket.on("connect", () => {
        console.log(socket.id);
        CONNECT_SOCKET = true;
    });

    // lắng nghe sự kiện từ xe command
    socket.on('vehicle', function (data) {
        console.log(data);

    });

    // lắng nghe messager từ serve
    socket.on('msg', function (data) {
        data = data.split(':')
        if (data[0] == 'done') {
            check_request = true;
            is_conect = true;
            alert(data[1]);
            $('.loading').addClass('hide');
            $('.tb-wrap').addClass('hide');
            $('.tb-control-wrap').removeClass('hide');
        }
        if (data[0] == 'err') {
            alert('Có lỗi xảy  ra với kết nối');
            is_conect = false;
        }
    });
    // lắng nghe messager từ serve
    socket.on('leave', function (data) {
        data = data.split(':')
        if (data[0] == 'done') {
            alert(data[1]);
            $('.tb-wrap').removeClass('hide');
            $('.tb-control-wrap').addClassClass('hide');
        }
        if (data[0] == 'err') {
            alert('Có lỗi xảy  ra với kết nối');
            is_conect = false;
        }
        $('.loading').addClass('hide');

    });
    // lắng nghe hình ảnh video từ camera
    socket.on('frame', function (data) {
        console.log(data);
        $('#video').attr('src', 'data:image/jpeg;base64,' + data);
    });

    // lắng nghe thông số từ sensor
    socket.on('sensor', function (data) {
        // if (is_conect === true) {
        data = JSON.parse(data);
        var html = `<p>> Cảm biến trái: ${data[0]} cm</p>`;
        html += `<p>> Cảm biến cận trái: ${data[1]} cm</p>`;
        html += `<p>> Cảm biến giữa: ${data[2]} cm</p>`;
        html += `<p>> Cảm biến cận phải: ${data[3]} cm</p>`;
        html += `<p>> Cảm biến phải: ${data[4]} cm</p>`;
        html += $('.consolebody').html();
        $('.consolebody').html(html)
        var i = 1;
        data.forEach(e => {

            $(`.sensor${i}-txt`).html(e);
            $(`.line-${i}`).attr('style', 'height: ' + e + '%');
            i++;
        })
        // }
    });

    // xử lý infor
    socket.on('infor', function (data) {
        // if (is_conect === true) {
        console.log(data);
        tmp = data;
        data = data.split(':')
        if (data[0] == 'list') {

            data = JSON.parse(data[1]);
            VEHICLE = []
            data.forEach(e => {
                if (!VEHICLE.includes(e)) {
                    VEHICLE.push(e);
                }
            })
            localStorage.setItem("vehicle", JSON.stringify(VEHICLE));
            render_vehicle();
        }
        if (data[0] == 'detail') {
            var a = tmp.replaceAll('detail:', '')

            data = JSON.parse(a);
            // gán lại mode

            if (data.mode == 'auto') {
                $('.vehicle-mode select').val(data.mode)
            }
            var member = data.members.length - 1;
            $('.vehicle-members span').html(member)

            // show so connect


        }
        $('.loading').addClass('hide');

        // }
    });
}
setInterval(() => {
    $('.consolebody').html(``)
}, 5000)

function send_ip(IP) {
    if (CONNECT_SOCKET == false) {
        alert('Service đang không hoạt động, Vui lòng thử lại');
        return false;
    }
    IP_CURRENT = IP;
    $('.loading').removeClass('hide');
    $('.vehecle-ip').html(IP_CURRENT);
    socket.emit('join', IP)
    check_request = false;
    setTimeout(() => {
        if (check_request == false) {
            alert('Kiểm tra lại kết nối hoặc xe đang không hoạt động!');
        }
    }, 3000)
}

function scan_vehicles() {
    if (CONNECT_SOCKET == false) {
        alert('Service đang không hoạt động, Vui lòng thử lại');
        return false;
    }
    $('.loading').removeClass('hide');
    socket.emit('infor', 'scan_vehicles')
    console.log('scan_vehicles');

    setTimeout(() => {
        if (check_request == false) {
            alert('Kiểm tra lại kết nối!');
        }
    }, 3000)

}
function swith_vehicles() {
    $('.loading').removeClass('hide');
    IP_CURRENT = '';
    socket.emit('leave', IP_CURRENT)
}
function swith_mode(self) {
    var val = $(self).val();
    if (val == undefined || val == '') {
        val = 'auto';
    }
    data = {
        'room_name': IP_CURRENT,
        'data': 'mode:' + val,
    }
    data = JSON.stringify(data);
    socket.emit('vehicle command', data);

}