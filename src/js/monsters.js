"use strict";

import * as Conf from './conf.js';
import * as Form from './form.js';


function create(e) {
    e.preventDefault();

    const type = "POST";
    const requestUrl = Conf.url.api + e.target.getAttribute('action');
    const formId = e.target.id;
    const data = Form.getData(formId);

    $.ajax({
        type        : type,
        url         : requestUrl,
        timeout     : 5000,
        header      : {},
        data        : JSON.stringify(data),
        processData : false,
        success     : (response, xhr) => {
            // do something here
            console.log(response);
            console.log(xhr);
            console.log("lol");
        },
        error       : (xhr) => {
            // do something for alert user
            console.log("status =" + xhr.status);
        },
        complete    : () => {
            Form.clear(formId);
        }
    });
}

export function init() {
    $('#newMstr').on('submit', create);
}