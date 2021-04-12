function initNumberInput(){
  $('.only-numbers, #infl, #pass_code').keydown(function (event) {
    event.stopPropagation();
    if (event.keyCode == 46 || event.keyCode == 8) {
    }
    else {
      if ((event.keyCode < 48 || event.keyCode > 57) &&
         event.keyCode < 96 || event.keyCode > 105) {
        event.preventDefault();
      }
    }
  });
}

function pinflcheck(value, colname) {
  if (colname == 'ПИНФЛ' && value.length == 14) {
      return [true, "", ""];
  } else {
      return [false, "Pinfl 14 ta sondan tashkil topgan bo'lishi kerak", ""];
  }
}

function initPinflInput(){
  $('.pinfl, #infl').on('change', function (event) {
    event.stopPropagation();
    var pinfl = $(this).val();
    if(!/\d{14}/.exec(pinfl)){
      $(this).val('');
    }
  });
}
