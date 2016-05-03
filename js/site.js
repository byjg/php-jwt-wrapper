/**
 * Created by jg on 03/05/16.
 */
var teste = teste || {};

teste.doLogin = function() {
    $.post('login.php', null, function(data){
        store.setJWT(data);
        console.log('Logged in: ' + store.getJWT());
    }).fail(function(){
        alert('error');
    });
}

teste.callApi = function () {
    $.ajax({
        url: 'api.php',
        beforeSend: function(request){
            request.setRequestHeader('Authorization', 'Bearer ' + store.getJWT());
        },
        type: 'GET',
        success: function(data) {
            console.log('OK: '  + data);
        },
        error: function() {
            alert('error');
        }
    });
}
