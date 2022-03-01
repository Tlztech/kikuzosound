$(document).ready(function () {
  function formValidation() {
    $("#email").on("keyup", function () {
      var email = $("#email").val();
      var test_email = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
      if (!test_email.test(email)) {
        $(".invalid_email").show();
      } else {
        $(".invalid_email").hide();
      }
    });

    $("#phone_number").on("keyup", function () {
      var phone = $("#phone_number").val();
      var testPhone = /^\s*(?:\+?(\d{1,3}))?[-. (]*(\d{3})[-. )]*(\d{3})[-. ]*(\d{4})(?: *x(\d+))?\s*$/;
      if (!testPhone.test(phone) || phone.length > 11) {
        $(".invalid_phone").show();
      } else {
        $(".invalid_phone").hide();
      }
    });

    $("#zip_code").on("keyup", function () {
      var zip_code = $("#zip_code").val();
      if (zip_code.length != 7) {
        $(".invalid_zip").show();
      } else {
        $(".invalid_zip").hide();
      }
    });
  }

  formValidation();
});
