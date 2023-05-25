const veri_code = document.querySelectorAll(".veri_code");
let otp = "";

veri_code[0].focus();

const otpPaste = e => {
  const data = e.clipboardData.getData("text");
  const value = data.split("");
  if(value.length === veri_code.length){
    veri_code.forEach((input, index) => {
      input.value = value[index]
      veri_code[veri_code.length - 1].focus();
    });
  }
}

veri_code.forEach((code, index) => {
  code.addEventListener("keydown", (e) => {
    if (e.key >= 0 && e.key <= 9) {
      veri_code[index].value = "";
      setTimeout(() => veri_code[index + 1].focus(), 10);
    } else if (e.key === "Backspace") {
      setTimeout(() => veri_code[index - 1].focus(), 10);
    }
  });
  code.addEventListener("paste", otpPaste)
});


$(document).ready(function () {
  $("#verify").click(function () {
    let otp1 = $('#otp1').val();
    let otp2 = $('#otp2').val();
    let otp3 = $('#otp3').val();
    let otp4 = $('#otp4').val();
    let otp5 = $('#otp5').val();
    let otp6 = $('#otp6').val();

    let otpCode = otp1 + '' + otp2 + '' + otp3 + '' + otp4 + '' + otp5 + '' + otp6;

    if (otp1 != "" && otp2 != "" && otp3 != "" && otp4 != "" && otp5 != "" && otp6 != "") {
      $.ajax({
        url: "../patientSide/verifyLogic.php",
        type: "POST",
        data: {
          otp: otpCode
        }, 
        success: function (data) {
          alert(data);
          if (data !== "Ky kod nuk eshte i sakte! Ju lutemi provojeni perseri." && data !== "Ky kod ka skaduar!") {
            window.location.replace('../index.php');
          }
        }
      });
    } else {
      veri_code.forEach(code => {
        if (code.value === "") {
          code.style.borderColor = 'red';
        } else {
          code.style.borderColor = '';
        }
      });
    }
  });
});