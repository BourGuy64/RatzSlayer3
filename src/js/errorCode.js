"use strict";

export function errorCode(code, msg) {
  if(code === 0)
  {
    success(msg);
  }
  else
  {
    error(msg);
  }
}

function success(msg) {

  $('#myModal > div').removeClass("modal-content-false");
  $('#myModal > div').addClass("modal-content-true");

  $('#myModal p').text(msg);
  $('#myModal').show();

  console.log("success");
  $('#myModal span').on("click", () => {
    $('#myModal').hide();
  });
}

function error(msg) {
  $('#myModal > div').removeClass("modal-content-true");
  $('#myModal > div').addClass("modal-content-false");

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
