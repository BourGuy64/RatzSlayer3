"use strict";

// collect value of input text
function getDataText(formId, formData) {
    let key,
        value;

    $('#' + formId + ' input').each(function() {
        key = $(this).attr('id');
        value = $(this).val();
        formData[key] = value;
    });
}

// collect value of select
function getDataSelect(formId, formData) {
    let key,
        value;

    $('#' + formId + ' select').each(function() {
        key = $(this).attr('id');
        value = $(this).val();
        formData[key] = value;
    });
}

export function getData(formId) {
    let formData = {};

    formData = getDataText(formId, formData);
    formData = getDataSelect(formId, formData);

    return formData;
}

export function clear(formId) {
    $('#' + formId + ' :input').val("")
        .siblings("label").removeClass("active");
}
