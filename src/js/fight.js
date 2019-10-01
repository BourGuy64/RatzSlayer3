"use strict"

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

export function init() {
    $('.select-char').on('click', selectChar);
    $('.select-monster').on('click', selectMonster);
}
