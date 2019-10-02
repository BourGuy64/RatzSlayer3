"use strict";

function iconeSpin() {
    $(this).addClass('fa-spin');
}

function iconeSpinStop() {
    $(this).removeClass('fa-spin');
}

export function init() {
    $('.fa-cog').on('mouseover', iconeSpin);
    $('.fa-cog').on('mouseout', iconeSpinStop);
}
