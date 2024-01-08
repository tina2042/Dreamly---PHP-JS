document.addEventListener("DOMContentLoaded", function () {
    // Get all comment icons
    const commentIcons = document.querySelectorAll(".comment_icon");

    // Add click event listener to each comment icon
    commentIcons.forEach(function (icon) {
        icon.addEventListener("click", function () {
            // Toggle the display of comments for the corresponding dream
            let dreamComments = icon.closest(".my-dream").querySelector(".dream-comments");
            dreamComments.classList.toggle("show-comments");
        });
    });
});