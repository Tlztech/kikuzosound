$(document).ready(function () {
  function checkPasswords() {
    var test_password = /^(?=.*[a-zA-Z])(?=\w*[0-9])\w{6,}$/;
    $("#teacher_pass").on("keyup", function () {
      var teacher_pass = $("#teacher_pass").val();
      if (teacher_pass.length < 6) {
        $(".min_char_err").show();
      } else {
        $(".min_char_err").hide();
        if (!test_password.test(teacher_pass)) {
          $(".invalid_pass").show();
        } else {
          $(".invalid_pass").hide();
        }
      }
    });

    $("#student_pass").on("keyup", function () {
      var student_pass = $("#student_pass").val();
      if (student_pass.length < 6) {
        $(".min_char_err_2").show();
      } else {
        $(".min_char_err_2").hide();
        if (!test_password.test(student_pass)) {
          $(".invalid_pass_2").show();
        } else {
          $(".invalid_pass_2").hide();
        }
      }
    });

    $("#username").on("keyup", function () {
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

    $("#disp_order").on("keyup", function () {
      $(".invalid_disp_order").hide();
      var disp_order = $("#disp_order").val();
      var test_disp_order = /^\d+(,\d+)*$/;
      if (!test_disp_order.test(disp_order)) {
        $(".invalid_disp_order").show();
      } else {
        $(".invalid_disp_order").hide();
      }
    });
  }

  function isValidPassword() {
    var isValid = true;
    $("#student_pass, #teacher_pass").each(function () {
      var re = /^(?=.*[a-zA-Z])(?=\w*[0-9])\w{6,}$/.test(this.value);
      if (!re || this.value.length < 6) {
        isValid = false;
        return false;
      }
    });
    return isValid;
  }

  function isValidDispOrder() {
    var isValid = true;
    $("#disp_order").each(function () {
      var disp_order = $("#disp_order").val();
      var test_disp_order = /^\d+(,\d+)*$/;
      if (!test_disp_order.test(disp_order)) {
        isValid = false;
        return false;
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
        if (isValidDispOrder() && isValidPassword() && isValidUsername()) {
          $(".submit_btn").attr("disabled", false);
        } else {
          $(".submit_btn").attr("disabled", true);
        }
      }
    );
  }

  disableSubmitBtn();
  checkPasswords();
});
