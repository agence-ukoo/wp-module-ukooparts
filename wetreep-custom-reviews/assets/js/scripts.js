(function ($) {
  $(document).ready(function () {
    $("#global-review-dialog").dialog({
      autoOpen: false,
      modal: true,
      width: 600,
      maxWidth: 600,
      height: 600,
      title: textContent.content,
      fluid: true,
    });
    $("#add-new-global-review-button").click(function () {
      $("#global-review-dialog").dialog("open");
    });
    $(".done-button").click(function () {
      $("#global-review-dialog").dialog("close");
    });
  });
  $("#accordionGroup").accordion({
    heightStyle: "content",
    icons: { header: "ui-icon-plus", activeHeader: "ui-icon-minus" },
  });
  // $(".global-review-rating").starRating({
  //   starSize: 20,
  //   strokeColor: "#894A00",
  //   strokeWidth: 10,
  //   disableAfterRate: false,
  //   starShape: "rounded",
  //   callback: function (currentRating, $el) {
  //     $("#global_trip_rating").val(currentRating);
  //   },
  // });
  var textareaValue = $(".cm-textarea").val();
  $(".trip-item-content").each(function () {
    var textareaInput = "";
    var button = $(this).children("button");
    // button.prop("disabled", true);
    $(this)
      .find(".cm-textarea")
      .on("input", function () {
        textareaInput = $(this).find(".cm-textarea").prevObject[0].value;
        // TODO: Check if the textarea is empty
        // if (textareaInput.length > 10) {
        //   button.prop("disabled", false);
        // } else {
        //   button.prop("disabled", true);
        // }
      });
  });
})(jQuery);
