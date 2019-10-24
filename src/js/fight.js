"use strict"

import * as Conf from './conf.js';
import Cookies from './../framework/js-cookie/js-cookie.js';

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

function displayRound($logs) {
    console.log($logs);

}

function getLastRound(fightId, fighterId, fighterType) {

    const requestUrl = Conf.url.api + "/fightlog/last/" + fightId + "/" + fighterType + "/" + fighterId;

    $.ajax({
        type        : 'GET',
        url         : requestUrl,
        success     : (response, xhr) => {
            // do something here
            console.log(response); // DEV Return what twig generate but in console...
            if (fighterType == 'c') {
                $('.fight-log.character').prepend(response);
            } else if (fighterType == 'm') {
                $('.fight-log.monster').prepend(response);
            }
        },
        error       : (xhr) => {
            // do something for alert user
            console.log("status =" + xhr.status); // DEV
        },
        complete    : () => {
        }
    });
}

function start(e) {
    e.preventDefault();

    const char = $('.select-char.is-selected').attr('data-id');
    const monster = $('.select-monster.is-selected').attr('data-id');
    const requestUrl = Conf.url.api + "/" + 'fight';

    $.ajax({
        type        : 'POST',
        url         : requestUrl,
        data        : {char: char, monster: monster},
        success     : (response, xhr) => {
            // do something here
            document.write(response);
        },
        error       : (xhr) => {
            // do something for alert user
            console.log("status =" + xhr.status); // DEV
        },
        complete    : () => {
        }
    });
}

function nextRound(e) {

    e.target.disabled = true;

    const type = "POST";
    const requestUrl = Conf.url.api + "/fightlog";

    const fightId = Cookies.get('fight');
    const char = $('.select-char').attr('data-id');
    const monster = $('.select-monster').attr('data-id');
    const formData = new FormData();
    formData.append("fightId", fightId);
    formData.append("char", char);
    formData.append("monster", monster);

    $.ajax({
        type        : type,
        url         : requestUrl,
        timeout     : 5000,
        header      : {},
        data        : formData,
        processData : false,
        contentType : false,
        success     : (response, xhr) => {
            // do something here
            getLastRound(fightId, char, 'c');
            getLastRound(fightId, monster, 'm');
        },
        error       : (xhr) => {
            // do something for alert user
            console.log("status =" + xhr.status); // DEV
            console.log(xhr);
        },
        complete    : () => {
            e.target.disabled = false;
        }
    });
}

export function init() {
    $('.select-char').on('click', selectChar);
    $('.select-monster').on('click', selectMonster);
    $('.cancel-fight').on('click', cancel);
    $('.start-fight').on('click', start);
    $('#next').on('click', nextRound);
}
