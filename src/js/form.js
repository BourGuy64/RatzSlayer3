"use strict";

// collect value of input text
function getDataText(formId, formData) {
    let key,
        value;

    $('#' + formId + ' :text').each(function() {
        key = $(this).attr('id');
        value = $(this).val();
        formData.append(key, value); // formData[key] = value;
    });

    return formData;
}

function getDataNumber(formId, formData) {
    let key,
        value;

    $(':input[type="number"]').each(function() {
        key = $(this).attr('id');
        value = $(this).val();
        formData.append(key, value); // formData[key] = value;
    });

    return formData;
}

// collect value of select
function getDataSelect(formId, formData) {
    let key,
        value;

    $('#' + formId + ' select').each(function() {
        key = $(this).attr('id');
        value = $(this).val();
        formData.append(key, value) // formData[key] = value;
    });

    return formData;
}

export function getData(formId) {
    let formData = new FormData();

    formData = getDataText(formId, formData);
    formData = getDataNumber(formId, formData);
    formData = getDataSelect(formId, formData);

    return formData;
}

export function getDataOld(formId) {
    let formData = {};

    formData = getDataText(formId, formData);
    formData = getDataNumber(formId, formData);
    formData = getDataSelect(formId, formData);

    return formData;
}

export function clear(formId) {
    $('#' + formId + ' :input').val("")
        .siblings("label").removeClass("active");
}
