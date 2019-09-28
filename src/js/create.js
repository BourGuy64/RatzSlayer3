"use strict";

import * as Conf from './conf.js';
import * as Form from './form.js';
import * as Dev from './dev.js';


function create(e) {
    e.preventDefault();

    const type = "POST";
    const requestUrl = Conf.url.api + "/" + e.target.getAttribute('action');
    const formId = e.target.id;
    const data = Form.getData(formId);

    $.ajax({
        type        : type,
        url         : requestUrl,
        timeout     : 5000,
        header      : {},
        data        : data,
        processData : false,
        contentType : false,
        success     : (response, xhr) => {
            // do something here
            console.log(response); // DEV
        },
        error       : (xhr) => {
            // do something for alert user
            console.log("status =" + xhr.status); // DEV
        },
        complete    : () => {
            Form.clear(formId);
        }
    });
}

export function init() {
    $('#newChar, #newMstr').on('submit', create);
}
