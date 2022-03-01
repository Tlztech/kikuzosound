$(document).ready(function () {
  function isEmpty() {
    $(".company, .yomi").on("keyup", function () {
      var company = $(".company").val();
      var yomi = $(".yomi").val();
      company ? $(".required1").hide() : $(".required1").show();
      company.length <= 30 ? $(".max_char1").hide() : $(".max_char1").show();
      yomi ? $(".required2").hide() : $(".required2").show();
      if (company && company.length <= 30 && yomi && yomi.length <= 30) {
        $(".submit_btn").attr("disabled", false);
      } else {
        $(".submit_btn").attr("disabled", true);
      }
    });
  }
  isEmpty();
});
