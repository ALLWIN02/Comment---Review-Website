$(document).ready(function () {
  fetchBusinesses();
  fetchTopRatedBusinesses();
  fetchMostReviewedBusinesses();

  $('a[href^="#"]').on("click", function (e) {
    e.preventDefault();
    var target = this.hash;
    $("html, body").animate(
      {
        scrollTop: $(target).offset().top,
      },
      1800
    );
  });
});

function fetchBusinesses() {
  $.ajax({
    url: "fetch_businesses.php",
    method: "GET",
    success: function (data) {
      $("#businessList").html(data);
    },
    error: function (xhr, status, error) {
      console.error("AJAX Error: " + status + error);
    },
  });
}

function fetchTopRatedBusinesses() {
  $.ajax({
    url: "fetch_top_rated_businesses.php",
    method: "GET",
    success: function (data) {
      $("#topRatedBusinesses").html(data);
    },
    error: function (xhr, status, error) {
      console.error("AJAX Error: " + status + error);
    },
  });
}

function fetchMostReviewedBusinesses() {
  $.ajax({
    url: "fetch_most_reviewed_businesses.php",
    method: "GET",
    success: function (data) {
      $("#mostReviewedBusinesses").html(data);
    },
    error: function (xhr, status, error) {
      console.error("AJAX Error: " + status + error);
    },
  });
}
