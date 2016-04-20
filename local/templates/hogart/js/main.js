"use strict";
var hogartApp = {};
$(function () {
    hogartApp = new HogartApp();
    hogartApp.init();
});

function HogartApp() {
}

HogartApp.prototype.init = function () {
    this.setHandlers();
};

HogartApp.prototype.setHandlers = function () {
    var self = this;
    $('.eventRegistrationForm').on('forms.submit.success', function(event, data){
        if(data.redirect) {
            var link = document.createElement('A');
            link.target = '_blank';
            link.style = 'display: none;';
            link.href = data.redirect;
            document.body.appendChild(link);
            link.click();
        }
    });
};