$(document).ready(function () {
  function validations() {
    var test_password = /^(?=.*[a-zA-Z])(?=\w*[0-9])\w{6,}$/;
    $("#teacher_pass").on("keyup", function () {
      var teacher_pass = $("#teacher_pass").val();
      if (teacher_pass.length == 0) {
        $(".min_char_err").hide();
        $(".invalid_pass").hide();
      } else {
        if (teacher_pass.length < 6 && teacher_pass.length > 0) {
          $(".min_char_err").show();
        } else {
          $(".min_char_err").hide();
          if (!test_password.test(teacher_pass)) {
            $(".invalid_pass").show();
          } else {
            $(".invalid_pass").hide();
          }
        }
      }
    });

    $("#student_pass").on("keyup", function () {
      var student_pass = $("#student_pass").val();
      if (student_pass.length == 0) {
        $(".min_char_err_2").hide();
        $(".invalid_pass_2").hide();
      } else {
        if (student_pass.length < 6 && student_pass.length > 0) {
          $(".min_char_err_2").show();
        } else {
          $(".min_char_err_2").hide();
          if (!test_password.test(student_pass)) {
            $(".invalid_pass_2").show();
          } else {
            $(".invalid_pass_2").hide();
          }
        }
      }
    });

    $("#username").keyup(function () {
      var username = $("#username").val();
      var test_username = /^[a-z0-9]+$/i;
      if (username.length < 6 || username.length > 30) {
        $(".min_char_user").show();
      } else {
        $(".min_char_user").hide();
        if (!test_username.test(username)) {
          $(".invalid_user").show();
        } else {
          $(".invalid_user").hide();
        }
      }
    });
  }

  function isValidPassword() {
    var isValid = true;
    $("#student_pass, #teacher_pass").each(function () {
      var re = /^(?=.*[a-zA-Z])(?=\w*[0-9])\w{6,}$/.test(this.value);
      if (this.value.length != 0) {
        if (!re || this.value.length < 6) {
          isValid = false;
          return false;
        }
      } else {
        isValid = true;
        return true;
      }
    });
    return isValid;
  }

  function isValidUsername() {
    var isValid = true;
    $("#username").each(function () {
      var username = $("#username").val();
      var test_username = /^[a-z0-9]+$/i;
      if (
        !test_username.test(username) ||
        username.length < 6 ||
        username.length > 30
      ) {
        isValid = false;
        return false;
      } else {
        isValid = true;
        return true;
      }
    });
    return isValid;
  }

  function disableSubmitBtn() {
    $("#student_pass, #teacher_pass, #disp_order, #username").on(
      "keyup",
      function () {
        if (isValidPassword() && isValidUsername()) {
          $(".submit_btn").attr("disabled", false);
        } else {
          $(".submit_btn").attr("disabled", true);
        }
      }
    );
  }

  validations();
  disableSubmitBtn();
});
