var app = app || {};

app.doLogin = function() {
    var me = this;
    $.post('login.php', null, function(data){
        store.setJWT(data);
        me.log('Logged in: ' + store.getJWT());
    }).fail(function(){
        alert('error');
    });
};

app.callApi = function () {
    var me = this;
    $.ajax({
        url: 'api.php',
        beforeSend: function(request){
            request.setRequestHeader('Authorization', 'Bearer ' + store.getJWT());
        },
        type: 'GET',
        success: function(data) {
            me.log('OK: '  + data);
        },
        error: function(data) {
            me.log('ERROR: ' + data.responseText);
        }
    });
};

app.doLogout = function() {
    store.clearJWT();
    this.log('Logged out');
};

app.log = function(msg) {
    document.getElementById("result").innerHTML += msg + "<br>";
};
