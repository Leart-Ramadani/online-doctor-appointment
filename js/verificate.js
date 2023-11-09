const veri_code = document.querySelectorAll(".veri_code");
let otp = "";

veri_code[0].focus();

const otpPaste = e => {
  const data = e.clipboardData.getData("text");
  const value = data.split("");
  if (value.length === veri_code.length) {
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





const verifyBtn = document.querySelector('.verify');
const btnLoader = document.querySelector('.btnLoader');
const btnText = document.querySelector('.btnText');


const sendOtp = () => {
  let otpCode = '';
  veri_code.forEach(code => {
    otpCode += code.value;
  });

  if (otpCode.length == 6) {
    veri_code.forEach(code => {
      code.style.borderColor = '';
    });

    btnLoader.classList.remove('d-none');
    btnText.innerHTML = 'Verifying...';
    verifyBtn.classList.add('disabled');
    $.ajax({
      url: '../patientSide/verifyLogic.php',
      type: 'POST',
      data: {
        otpCode: otpCode
      },
      success: response => {
        console.log(response);
        if (response == 'verified') {
          btnLoader.classList.add('d-none');
          btnText.innerHTML = 'Verified';
          verifyBtn.disabled = false;
          setTimeout(() => {
            window.location.replace('../patientSide/rezervoTermin.php');
          }, 500)
        } else if (response == 'something went wrong') {
          btnLoader.classList.add('d-none');
          btnText.innerHTML = 'Something went wrong';
          verifyBtn.disabled = false;
        } else if (response == 'expierd code') {
          alert('This code has expired. Check your email for the new code');
          document.querySelector('.veri-form-wrapper').classList.add('d-none')
          document.querySelector('.loaderWrapper').classList.remove('d-none');
          btnLoader.classList.add('d-none');
          btnText.innerHTML = 'Verify';
          verifyBtn.disabled = false;
          window.location.replace("../patientSide/resendCode.php");
        } else if (response == 'wrong code') {
          btnLoader.classList.add('d-none');
          btnText.innerHTML = 'Wrong code';
          verifyBtn.disabled = false;
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

}

veri_code.forEach(code => {
  code.addEventListener('keyup', e => {
    if (e.key === 'Enter') {
      sendOtp();
    }
  });
});


verifyBtn.addEventListener('click', sendOtp);