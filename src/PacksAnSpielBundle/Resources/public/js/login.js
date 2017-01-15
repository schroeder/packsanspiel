var gCtx = null;
var gCanvas = null;
var c = 0;
var stype = 0;
var gUM = false;
var webkit = false;
var moz = false;
var v = null;

var vidhtml = '<video id="v" autoplay></video>';

function initCanvas(w, h) {
    gCanvas = document.getElementById("qr-canvas");
    gCanvas.style.width = w + "px";
    gCanvas.style.height = h + "px";
    gCanvas.width = w;
    gCanvas.height = h;
    gCtx = gCanvas.getContext("2d");
    gCtx.clearRect(0, 0, w, h);
}

function captureToCanvas() {
    if (stype != 1)
        return;
    if (gUM) {
        try {
            gCtx.drawImage(v, 0, 0);
            try {
                qrcode.decode();
            }
            catch (e) {
                console.log(e);
                setTimeout(captureToCanvas, 500);
            }
            ;
        }
        catch (e) {
            console.log(e);
            setTimeout(captureToCanvas, 500);
        }
        ;
    }
}

function read(a) {
    if (isValidMD5(a)) {
        /*
         * TODO: CHeck QR Code
         * */
        window.location = "/PacksAnSpiel/web/app_dev.php/login?qr=" + a;
    }
    else {
        document.getElementById("login_result").innerHTML = "DAS HAT LEIDER NICHT GEKLAPPT!";
        document.getElementById("login_result").style.backgroundColor = "#FF0000";
        document.getElementById("login_result").style.color = "#EEEEEC";
        setTimeout(setDefaultText, 3000);

    }
}

function setDefaultText() {
    document.getElementById("login_result").innerHTML = "ZUM EINLOGGEN KARTE IN DEN SCHLITZ FÜHREN!";
    document.getElementById("login_result").style.backgroundColor = "#EEEEEC";
    document.getElementById("login_result").style.color = "#000";
}

function isValidMD5(s) {
    /*    return s.matches("[a-fA-F0-9]{32}");*/
    return s.toString().match("[a-fA-F0-9]{32}");
}

function isCanvasSupported() {
    var elem = document.createElement('canvas');
    return !!(elem.getContext && elem.getContext('2d'));
}
function success(stream) {
    if (webkit)
        v.src = window.URL.createObjectURL(stream);
    else if (moz) {
        v.mozSrcObject = stream;
        v.play();
    }
    else
        v.src = stream;
    gUM = true;
    setTimeout(captureToCanvas, 500);
}

function error(error) {
    gUM = false;
    return;
}

function load() {
    if (isCanvasSupported() && window.File && window.FileReader) {
        initCanvas(800, 600);
        qrcode.callback = read;
        document.getElementById("main").style.display = "inline";
        setwebcam();
    }
    else {
        document.getElementById("main").style.display = "inline";
        document.getElementById("main").innerHTML = '<p id="mp1">QR code scanner for HTML5 capable browsers</p><br>' +
            '<br><p id="mp2">sorry your browser is not supported</p><br><br>';
    }
}

function setwebcam() {
    var options = true;
    if (navigator.mediaDevices && navigator.mediaDevices.enumerateDevices) {
        try {
            navigator.mediaDevices.enumerateDevices()
                .then(function (devices) {
                    devices.forEach(function (device) {
                        if (device.kind === 'videoinput') {
                            if (device.label.toLowerCase().search("back") > -1)
                                options = {'deviceId': {'exact': device.deviceId}, 'facingMode': 'environment'};
                        }
                        console.log(device.kind + ": " + device.label + " id = " + device.deviceId);
                    });
                    setwebcam2(options);
                });
        }
        catch (e) {
            console.log(e);
        }
    }
    else {
        console.log("no navigator.mediaDevices.enumerateDevices");
        setwebcam2(options);
    }

}

function setwebcam2(options) {
    console.log(options);
    if (stype == 1) {
        setTimeout(captureToCanvas, 500);
        return;
    }
    var n = navigator;
    document.getElementById("outdiv").innerHTML = vidhtml;
    v = document.getElementById("v");


    if (n.getUserMedia) {
        webkit = true;
        n.getUserMedia({video: options, audio: false}, success, error);
    }
    else if (n.webkitGetUserMedia) {
        webkit = true;
        n.webkitGetUserMedia({video: options, audio: false}, success, error);
    }
    else if (n.mozGetUserMedia) {
        moz = true;
        n.mozGetUserMedia({video: options, audio: false}, success, error);
    }

    stype = 1;
    setTimeout(captureToCanvas, 500);
}
