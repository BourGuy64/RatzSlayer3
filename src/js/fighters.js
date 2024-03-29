"use strict";

import * as Conf from './conf.js';
import * as Form from './form.js';
import * as CodeErr from './errorCode.js';


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
            CodeErr.errorCode(response.error_code, response.message);
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

function update(e) {
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
            CodeErr.errorCode(response.error_code, response.message);
        },
        error       : (xhr) => {
            // do something for alert user
            console.log("status =" + xhr.status); // DEV
        },
        complete    : () => {
        }
    });

}

function remove(e) {

    let fighterType;
    if ($(e.target).parent().parent().hasClass('monsters')) {
        fighterType = 'monsters';
    } else if ($(e.target).parent().parent().hasClass('characters')) {
        fighterType = 'characters';
    }
    console.log(fighterType);

    const type = "DELETE";
    const requestUrl = Conf.url.api + "/" + fighterType + "/" + $(e.target).data('id');
    console.log(requestUrl);


      $.ajax({
          type        : type,
          url         : requestUrl,
          timeout     : 5000,
          header      : {},
          data        : null,
          processData : false,
          contentType : false,
          success     : (response, xhr) => {
              $(e.target).parent().parent().remove();
          },
          error       : (xhr) => {
              // do something for alert user
              console.log("status =" + xhr.status); // DEV
          },
          complete    : () => {
          }
      });

}

function confirm_del(e) {
  $('#myModalDelete p').text("Etes-vous sûr de vouloir le supprimer ?");
  $('#myModalDelete').show();

  console.log("delete !");

  $('.cancel').on("click", () => {
    $('#myModalDelete').hide();
    console.log("delete cancel !");
  });


  $('.ok').on("click", () => {
    $('#myModalDelete').hide();
    console.log("delete ok !");
    remove(e);
  });
}

export function init() {
    $('#newChar, #newMstr').on('submit', create);
    $('#editMstr, #editCar').on('submit', update);
    $('.fighter .remove').on('click', confirm_del);
}
