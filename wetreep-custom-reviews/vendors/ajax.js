jQuery(function ($) {
  // handle submit review form from the front end
  $("document").ready(function () {
    $("#review-form").submit(function (e) {
      e.preventDefault();
      var form = $("#review-form :input");

      var values = {};

      $.each(form, function (i, v) {
        values[v.name] = v.value;
      });

      $.extend(values, { action: "wt_reviews_post_review" });

      $.ajax({
        url: wt_reviews_ajax_object.ajax_url,
        type: "POST",
        data: values,
        success: function (response) {
          $("#submit-message").empty();
          var jsonResponse = jQuery.parseJSON(response);
          $("#submit-message").append(jsonResponse.message);
          if (jsonResponse.succes === true) {
            $("#review-dialog").dialog("close");
          } else {
            setTimeout(function () {
              $("#review-dialog").dialog("close");
            }, 5000);
          }
        },
      });
    });
    $(".review-answer-form").each(function (index) {
      console.log(this);
      $this = $(this);
      $this.submit(function (e) {
        e.preventDefault();
        var form = $(":input", this);

        var values = {};

        $.each(form, function (i, v) {
          values[v.name] = v.value;
        });

        $.extend(values, { action: "wt_reviews_post_review_answer" });

        $.ajax({
          url: wt_reviews_ajax_object.ajax_url,
          type: "POST",
          data: values,
          success: function (response) {
            $("#submit-answer-message").empty();
            $(".asnwer-form-container").empty();
            var jsonResponse = jQuery.parseJSON(response);
            $("#submit-answer-message").append(jsonResponse.message);
          },
        });
      });
    });
  });
});
