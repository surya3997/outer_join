function myFunction(x) {
    x.classList.toggle("fa-thumbs-up");
}

$(document).ready(function () {
    append_prefix = '<li><a href="#">';
    append_suffix = '</a></li>';

    $.post("./ajax/getNotification.php", {}, function (response) {
        jsondata = JSON.parse(response);
        if (jsondata["status"] == 'Success') {
            var notes = jsondata['data'][0]['notification'];
            var n = Object.keys(notes).length;
            for (var i = 0; i < n; i++) {
                var append_string = append_prefix + notes[i]['notify_txt'] + append_suffix;
                $("#notify_append").append(append_string);
            }
        } else {
            alert(jsondata["data"]);
        }
    });
});

var me = {};

var you = {};

function formatAMPM(date) {
    var hours = date.getHours();
    var minutes = date.getMinutes();
    var ampm = hours >= 12 ? 'PM' : 'AM';
    hours = hours % 12;
    hours = hours ? hours : 12; // the hour '0' should be '12'
    minutes = minutes < 10 ? '0' + minutes : minutes;
    var strTime = hours + ':' + minutes + ' ' + ampm;
    return strTime;
}

//-- No use time. It is a javaScript effect.
function insertChat(who, text, speaker, time = 0) {
    var control = "";
    var date = formatAMPM(new Date());

    if (who == "me") {

        control = '<li style="width:100%" tabindex="1">' +
            '<div class="msj macro">' +
            '<div class="text text-l">' +
            '<p><b>' + speaker + '</b><br>' + text + '</p>' +
            '<p><small>' + date + '</small></p>' +
            '</div>' +
            '</div>' +
            '</li>';


    } else {
        control = '<li style="width:100%;" tabindex="1">' +
            '<div class="msj-rta macro">' +
            '<div class="text text-r">' +
            '<p><b>' + speaker + '</b><br>' + text + '</p>' +
            '<p><small>' + date + '</small></p>' +
            '</div>' +
            '<div class="avatar" style="padding:0px 0px 0px 10px !important"></div>' +
            '</li>';

        //$('#chat_scroll').scrollTop($('#chat_scroll')[0].scrollHeight);
    }
    setTimeout(
        function () {
            $("#chat_scroll").append(control);
            $('#chat_scroll').scrollTop($('#chat_scroll')[0].scrollHeight);
        }, time);

}

function resetChat() {
    $("#chat_scroll").empty();
}

function logout() {
    //alert("logout clicked");
    $.post("./ajax/logout.php", {}, function (response) {
        console.log(response);
        jsondata = JSON.parse(response);
        if (jsondata["status"] == 'Success') {
            window.location = './index.php';
        } else {
            alert(jsondata["data"]);
        }
    });
}

$(".mytext").on("keyup", function (e) {
    if (e.which == 13) {
        var text = $(this).val();
        if (text !== "") {
            insertChat("me", text);
            $(this).val('');
        }
    }
});

//-- Clear Chat
resetChat();

//-- Print Messages
insertChat("me", "Hello Tom...", "Spaceman", 0);
insertChat("you", "Hi, Pablo", "Spaceman", 1500);
insertChat("me", "What would you like to talk about today?", "Spaceman", 3500);
insertChat("you", "Tell me a joke", "Spaceman", 7000);
insertChat("me", "Spaceman: Computer! Computer! Do we bring battery?!", "Spaceman", 9500);
insertChat("you", "LOL", "Spaceman", 12000);
insertChat("you", "LOL", "Spaceman", 12000);
insertChat("you", "LOL", "Spaceman", 12000);
insertChat("you", "LOL", "Spaceman", 12000);


//-- NOTE: No use time on insertChat.
