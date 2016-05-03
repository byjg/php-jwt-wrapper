/**
 * Created by jg on 03/05/16.
 */
var store = store || {};

/*
 * Sets the jwt to the store object
 */
store.setJWT = function (data) {
    this.JWT = data;
};

store.getJWT = function () {
    return this.JWT;
};

