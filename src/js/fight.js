"use strict"

import * as Conf from './conf.js';
import * as Form from './form.js';

function selectChar(e){
  $('.select-char').each(function(i){
    $(this).removeClass('is-selected');
  });
  $(this).addClass('is-selected');
  fightReady();
}

function selectMonster(e){
  $('.select-monster').each(function(i){
    $(this).removeClass('is-selected');
  });
  $(this).addClass('is-selected');
  fightReady();
}

function fightReady(){
  let ready = 0;
  $('.select-monster').each(function(i){
    if($(this).hasClass('is-selected') == 1){
      ready++;
    }
  });
  $('.select-char').each(function(i){
    if($(this).hasClass('is-selected') == 1){
      ready++;
    }
  });
  if(ready === 2){
    $('.popup-fight, .overlay').removeClass('disable');
  }
}

function cancel(){
  $('.popup-fight, .overlay').addClass('disable');
  $('.select-char').each(function(i){
    $(this).removeClass('is-selected');
  });
  $('.select-monster').each(function(i){
    $(this).removeClass('is-selected');
  });
}

function start(e) {
    e.preventDefault();

    const char = $('.select-char.is-selected').attr('data-id');
    const monster = $('.select-monster.is-selected').attr('data-id');

    const type = "POST";
    const requestUrl = Conf.url.api + "/" + 'fight';
    const data = {'char': char, 'monster': monster};

    $.ajax({
        type        : type,
        url         : requestUrl,
        timeout     : 5000,
        header      : {},
        data        : JSON.stringify(data),
        processData : false,
        contentType : false,
        dataType: 'html',
        success     : (response, xhr) => {
            // do something here
            console.log(response); // DEV
        },
        error       : (xhr) => {
            // do something for alert user
            console.log("status =" + xhr.status); // DEV
            console.log(data);
        },
        complete    : () => {
            console.log('AIE');
        }
    });
}

export function init() {
    $('.select-char').on('click', selectChar);
    $('.select-monster').on('click', selectMonster);
    $('.cancel-fight').on('click', cancel);
    $('.start-fight').on('click', start);
}
