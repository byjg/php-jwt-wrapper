var store = store || {};

/*
 * Sets the jwt to the store object
 */
store.setJWT = function (data) {
    localStorage.setItem('tokenJwt', data);
};

store.getJWT = function () {
    return localStorage.getItem('tokenJwt');
};

store.clearJWT = function () {
    localStorage.removeItem('tokenJwt');
};

