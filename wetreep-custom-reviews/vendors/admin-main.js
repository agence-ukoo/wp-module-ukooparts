jQuery(function ($) {
  $(document).ready(function () {
    var currentRating = $("#admin-rating-stars").val();
    $(".admin-review-rating").starRating({
      initialRating: currentRating,
      starSize: 20,
      strokeColor: "#894A00",
      strokeWidth: 10,
      disableAfterRate: false,
      starShape: "rounded",
      callback: function (currentRating, $el) {
        $("#admin-rating-stars").val(currentRating);
      },
    });
  });
});
