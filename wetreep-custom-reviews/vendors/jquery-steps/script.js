var steps = $("#wizard").steps({
  showFooterButtons: false,
});
steps_api = steps.data("plugin_Steps");

(function ($) {
  $(document).ready(function () {
    $(".trip-item-content").each(function () {
      var thisParent = $(this);
      // $(this)
      //   .find(".global-review-rating")
      //   .starRating({
      //     starSize: 30,
      //     strokeColor: "#894A00",
      //     strokeWidth: 10,
      //     disableAfterRate: false,
      //     starShape: "rounded",
      //     callback: function (currentRating, $el) {
      //       thisParent.find(".global_trip_rating").val(currentRating);
      //     },
      //   });
      $(this)
        .find(".global-review-rating")
        .rate({
          cursor: "pointer",
          symbols: {
            utf8_hexagon: {
              base: '<img src="../../../../../../wp-content/plugins/wetreep-custom-reviews/vendors/rater/star_empty.png" width="24" height="24"/>',
              hover:
                '<img src="../../../../../../wp-content/plugins/wetreep-custom-reviews/vendors/rater/star_full.png" width="24" height="24"/>',
              selected:
                '<img src="../../../../../../wp-content/plugins/wetreep-custom-reviews/vendors/rater/star_full.png" width="24" height="24"/>',
            },
          },
          selected_symbol_type: "utf8_hexagon",
          convert_to_utf8: false,
        });
      $(this)
        .find(".global-review-rating")
        .on("change", function () {
          thisParent.find(".global_trip_rating").val($(this).rate("getValue"));
        });
      function handleClick(e) {
        steps_api.setStepIndex(2);
        steps_api.next();
        console.log($(e.data.formContainer).find(".cm-textarea").val());
        console.log($(e.data.formContainer).find(".global_trip_rating").val());
        $.ajax({
          url: dataObj.ajaxurl,
          type: "POST",
          data: {
            action: "wt_reviews_global_trip_review",
            review_text: $(e.data.formContainer).find(".cm-textarea").val(),
            post_id: $(e.data.formContainer).find("#tripid").val(),
            rating: $(e.data.formContainer).find(".global_trip_rating").val(),
          },
          success: function (res) {
            var data = JSON.parse(res);
            if (data.success) {
              $(e.data.formContainer).find(".trip-form-container").empty();
              $(e.data.formContainer)
                .find(".trip-form-container")
                .html(data.content);
              $(e.data.formContainer).find("#trip-review-submit").remove();
              $(data.button).insertAfter(
                $(e.data.formContainer).find("#global_trip_rating")
              );
            }
          },
        });

        // Calling ajax function to load the participants of the trip in the next step
        $.ajax({
          url: dataObj.ajaxurl,
          type: "POST",
          data: {
            action: "wt_reviews_get_participants",
            trip_id: $(this).data("trip-id"),
          },
          beforeSend: function () {
            $(".step-tab-panel .loading-rec").show();
          },
          success: function (data) {
            $(".step-tab-panel .loading-rec").hide();
            var res = JSON.parse(data);
            $(".participants-list").html(res.content);
          },
        });
      }
      $(".reviews-next", this).bind(
        "click",
        { formContainer: this },
        handleClick
      );

      $(".reviews-next-without-submit", this).on("click", function (e) {
        steps_api.setStepIndex(2);
        steps_api.next();
        // Calling ajax function to load the participants of the trip in the next step
        $.ajax({
          url: dataObj.ajaxurl,
          type: "POST",
          data: {
            action: "wt_reviews_get_participants",
            trip_id: $(this).data("trip-id"),
          },
          beforeSend: function () {
            $(".step-tab-panel .loading-rec").show();
          },
          success: function (data) {
            $(".step-tab-panel .loading-rec").hide();
            var res = JSON.parse(data);
            $(".participants-list").html(res.content);
          },
        });
      });
    });
    $(".reviews-prev").on("click", function () {
      $(".participants-list").empty();
      steps_api.prev();
    });
  });
})(jQuery);
