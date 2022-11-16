jQuery(function ($) {
  $(document).ready(function () {
    var reviewPageReview = $("#review-page-id").val();
    var reviewPageAuthor = $("#author-rating-field").val();
    $(".review-rating").starRating({
      initialRating: 4,
      starSize: 20,
      strokeColor: "#894A00",
      strokeWidth: 10,
      disableAfterRate: false,
      starShape: "rounded",
      callback: function (currentRating, $el) {
        $("#wt_user_rating").val(currentRating);
      },
    });
    $(".read-only-rating").starRating({
      readOnly: true,
      initialRating: reviewPageReview,
      starSize: 20,
      strokeColor: "#894A00",
      strokeWidth: 10,
      disableAfterRate: false,
      starShape: "rounded",
      callback: function (currentRating, $el) {
        var rating = data("rating");
      },
    });
    $(".author-rating").starRating({
      readOnly: true,
      initialRating: reviewPageAuthor,
      starSize: 20,
      strokeColor: "#894A00",
      strokeWidth: 10,
      disableAfterRate: false,
      starShape: "rounded",
      callback: function (currentRating, $el) {
        var rating = data("rating");
      },
    });
    $(".profile-trip-review").each(function () {
      var rating = $(this).data("rating");
      $(".profile-author-rating", this).starRating({
        readOnly: true,
        initialRating: rating,
        starSize: 20,
        strokeColor: "#894A00",
        strokeWidth: 10,
        disableAfterRate: false,
        starShape: "rounded",
      });
    });
    $(".trip-review-item").each(function () {
      var rating = $(this).data("rating");
      $(".trip-review-rate", this).starRating({
        readOnly: true,
        initialRating: rating,
        starSize: 20,
        strokeColor: "#894A00",
        strokeWidth: 10,
        starShape: "rounded",
      });
    });
  });
});
