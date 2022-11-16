jQuery(function ($) {
  $(document).ready(function () {
    // var reviewPageReview = $("#review-page-id").val();
    // var reviewPageAuthor = $("#author-rating-field").val();
    $("#review-dialog").dialog({
      autoOpen: true,
      modal: true,
      width: "auto",
      maxWidth: 600,
      height: "auto",
      title: dialogBox.user,
      fluid: true,
    });
    $("#review-button").click(function () {
      $("#review-dialog").dialog("open");
    });
  });
});
