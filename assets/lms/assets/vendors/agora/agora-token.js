window.generateToken = function (callback) {
    const data = {
        channelName: channelName
    };

    $.post('/lms/agora/token', data, function (result) {
        if(result && typeof callback === "function") {
            callback(result.token);
        }
    }).fail(err => {

    })
}
