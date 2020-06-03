$(".delete-post-btn").on("click", function () {
  if (confirm("Are you sure?")) {
    return true;
  } else {
    return false;
  }
});
