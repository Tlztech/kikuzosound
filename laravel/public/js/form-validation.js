$(document).ready(function () { 
  //show and hide required text
  function formValidation() {
    $(
      "#company, #name, #userId, #cust_mail_address, #cust_mail_address_confirm, #password, #passwordConfirm, #otp_1, #otp_2, #otp_3, #otp_4"
    ).on("keyup", function () {
      checkValidation();
    });

    $("#cust_mail_address").on("keyup", function () {
      var email = $("#cust_mail_address").val();
      var testEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
      if (!testEmail.test(email)) {
        $(".invalid_email").show();
      } else {
        $(".invalid_email").hide();
      }
      checkConfirmEmail();
    });

    $("#cust_mail_address_confirm").on("keyup", function () {
      var email = $("#cust_mail_address_confirm").val();
      var testEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
      if (!testEmail.test(email)) {
        $(".invalid_email_confirm").show();
      } else {
        $(".invalid_email_confirm").hide();
      }
      checkConfirmEmail();
    });

    $("#distr_mail_address").on("keyup", function () {
      var dist_email = $("#distr_mail_address").val();
      var dist_testEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
      if (!dist_testEmail.test(dist_email)) {
        $(".invalid_email_dist").show();
      } else {
        $(".invalid_email_dist").hide();
      }
    });

    $("#otp_1, #otp_2, #otp_3, #otp_4").on("keyup", function () {
      var otp_1 = $("#otp_1").val();
      var otp_2 = $("#otp_2").val();
      var otp_3 = $("#otp_3").val();
      var otp_4 = $("#otp_4").val();
      var total_length = otp_1.length + otp_2.length +otp_3.length+otp_4.length
      var test_otp = /^\d+$/;
      if (!test_otp.test(otp_1) || !test_otp.test(otp_2) || !test_otp.test(otp_3) || !test_otp.test(otp_4) || total_length!=16) {
        $(".invalid_otp").show();
      } else {
        $(".invalid_otp").hide();
      }
    });

    // concatenate 4 input
    $("#otp_1, #otp_2, #otp_3, #otp_4").on("keyup", function (e) {
      $("#oneTimePassword").val(
        $("#otp_1").val() + "-" +
        $("#otp_2").val() + "-" +
        $("#otp_3").val() + "-" +
        $("#otp_4").val()
      )
    });

    $("#phone_number").on("keyup", function () {
      var phone = $("#phone_number").val();
      var testPhone = /^\s*(?:\+?(\d{1,3}))?[-. (]*(\d{3})[-. )]*(\d{3})[-. ]*(\d{4})(?: *x(\d+))?\s*$/;
      if (!testPhone.test(phone)) {
        $(".invalid_phone").show();
      } else {
        $(".invalid_phone").hide();
      }
    });
  }

  function checkValidation() {
    $("#company").val() ? $(".required1").hide() : $(".required1").show();
    $("#name").val() ? $(".required2").hide() : $(".required2").show();
    $("#userId").val() ? $(".required3").hide() : $(".required3").show();
    $("#cust_mail_address").val()
      ? $(".required4").hide()
      : $(".required4").show();
    $("#cust_mail_address_confirm").val()
      ? $(".required8").hide()
      : $(".required8").show();
    $("#password").val() ? $(".required5").hide() : $(".required5").show();
    $("#passwordConfirm").val()
      ? $(".required6").hide()
      : $(".required6").show();
    $("#passwordConfirm").val()
      ? $(".required6").hide()
      : $(".required6").show();
    $("#password").val().length < 6 ? $(".pass_err").show() : $(".pass_err").hide();
    $("#password").val() == $("#passwordConfirm").val()
      ? $(".mismatch_pass").hide()
      : $(".mismatch_pass").show();
    ($("#otp_1").val() && $("#otp_2").val() && $("#otp_3").val() && $("#otp_4").val())
      ? $(".required7").hide()
      : $(".required7").show();

  }

  function checkConfirmEmail() {
    if ($("#cust_mail_address").val() == $("#cust_mail_address_confirm").val()) {
      $(".invalid_email_confirm_match").hide();
    } else {
      $(".invalid_email_confirm_match").show();
    }
  }

  function isValidEmail() {
    var isValid = true;
    $("#cust_mail_address").each(function () {
      var re = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i.test(this.value);
      if (!re) {
        isValid = false;
        return false;
      }
    });
    return isValid;
  }

  function isValidEmailConfirm() {
    var isValid = true;
    $("#cust_mail_address_confirm").each(function () {
      var re = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i.test(this.value);
      if (!re) {
        isValid = false;
        return false;
      }
    });
    return isValid;
  }

  function isEmailMatch() {
    if ($("#cust_mail_address").val() == $("#cust_mail_address_confirm").val()) {
      return true;
    } else {
      return false;
    }
  }

  function isValidPhoneNumber() {
    var isValidPhone = true;
    $("#phone_number").each(function () {
      var re = /^\s*(?:\+?(\d{1,3}))?[-. (]*(\d{3})[-. )]*(\d{3})[-. ]*(\d{4})(?: *x(\d+))?\s*$/.test(this.value);
      if($(this).val().length != 0) {
        if (!re) {
          isValidPhone = false;
          return false;
        }
      } else {
        isValidPhone = true;
          return true;
      }
      
    });
    return isValidPhone;
  }

  function passwordMatch() {
    var isMatch = true;
    $("#password, #passwordConfirm").each(function () {
      if ($("#password").val() != $("#passwordConfirm").val()) {
        isMatch = false;
        return false;
      }
    });
    return isMatch;
  }

  function isOTPValid() {
    var isValidOTP = true;
    $("#otp_1, #otp_2, #otp_3, #otp_4").each(function () {
      var otp_1 = $("#otp_1").val();
      var otp_2 = $("#otp_2").val();
      var otp_3 = $("#otp_3").val();
      var otp_4 = $("#otp_4").val();
      var total_length = otp_1.length + otp_2.length + otp_3.length+ otp_4.length
      var test_otp = /^\d+$/;
      if (!test_otp.test(otp_1) || !test_otp.test(otp_2) || !test_otp.test(otp_3) || !test_otp.test(otp_4) || total_length<16) {
        isValidOTP = false;
        return false;
      }else {
        isValidOTP = true;
          return true;
      }
    });
    return isValidOTP;
  }

  function formIsComplete() {
    var isComplete = true;
    $(
      "#company, #name, #userId, #cust_mail_address, #password, #passwordConfirm, #otp_1, #otp_2, #otp_3, #otp_4"
    ).each(function () {
      $t = $(this);
      if ($t.val().length == 0) {
        isComplete = false;
        return false;
      }
    });
    $("#oneTimePassword").val(
      $("#otp_1").val() + "-" +
      $("#otp_2").val() + "-" +
      $("#otp_3").val() + "-" +
      $("#otp_4").val()
    )
    return isComplete;
  }

  function disableSubmitBtn() {
    !formIsComplete() ? $("span").show() : $("span").hide();
    $(
      "#company, #name, #userId, #cust_mail_address, #cust_mail_address_confirm, #password, #passwordConfirm, #otp_1, #otp_2, #otp_3, #otp_4, #phone_number, input[type='checkbox']"
    ).bind("keyup click", function () {
      if (!formIsComplete()) {
        $(".submit_btn").attr("disabled", true);
      }
      else {
        if (isOTPValid() && isValidEmail() && isValidEmailConfirm() && isEmailMatch() && isValidPhoneNumber() && $("input[type='checkbox']").is(":checked")) {
          $(".submit_btn").attr("disabled", false);
        } else {
          $(".submit_btn").attr("disabled", true);
        }
      }
    });
  }

  function passwordMasking(){
    $(".toggle-password").click(function() {
      var input = $($(this).attr("toggle"));
      if (input.attr("type") == "password") {
          input.attr("type", "text"); 
          $(".eye").hide()
          $(".eye-slash").show()
      } else {
          input.attr("type", "password");
          $(".eye").show()
          $(".eye-slash").hide()
      }
  });
  
  $(".toggle-passwordConfirm").click(function() {
      var input = $($(this).attr("toggle"));
      if (input.attr("type") == "password") {
          input.attr("type", "text"); 
          $(".eye-2").hide()
          $(".eye-slash-2").show()
      } else {
          input.attr("type", "password");
          $(".eye-2").show()
          $(".eye-slash-2").hide()
      }
  });
  
  $(".toggle-otp").click(function() {
      var input = $($(this).attr("toggle"));
      if (input.attr("type") == "password") {
          input.attr("type", "text"); 
          $(".eye-3").hide()
          $(".eye-slash-3").show()
      } else {
          input.attr("type", "password");
          $(".eye-3").show()
          $(".eye-slash-3").hide()
      }
  });
  }

  disableSubmitBtn();
  formValidation();
  checkValidation();
  passwordMasking();
});