"use strict";

export function errorCode(code, msg, element =null) {
  if(code === 0)
  {
    success(msg, element);
  }
  else
  {
    error(msg);
  }
}

function success(msg, element) {
  if(!element)
  {
    element = '#main';
  }

  $(element).prepend('<div class="successAlert">' + msg + '</div>');
  console.log("success");
}

function error(msg) {
  $('#myModal p').text(msg);
  $('#myModal').show();
  // $('#main').prepend('<div class="errorAlert">' + msg + '</div>');
  console.log("error");
  // // Get the <span> element that closes the modal
  // var span = document.getElementsByClassName("close")[0];

  // When the user clicks on <span> (x), close the modal
  $('#myModal span').on("click", () => {
    $('#myModal').hide();
  });
}
