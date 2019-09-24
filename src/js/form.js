"use strict";

export function getData(formId) {
    let object = {},
        key,
        value;

    // collect value of input text
    $('#' + formId + ' input').each(function() {
        key = $(this).attr('id');
        value = $(this).val();
        object[key] = value;
    });

    // collect value of select
    $('#' + formId + ' select').each(function() {
        key = $(this).attr('id');
        value = $(this).val();
        object[key] = value;
    });

    return object;
}

export function clear(formId) {
    $('#' + formId + ' :input').val("")
        .siblings("label").removeClass("active");
}
