const commentIcons = document.querySelectorAll(".comment_icon");
const likeButtons = document.querySelectorAll('.like');


commentIcons.forEach(commentIcon => {
    commentIcon.addEventListener('click',  showComments);
});

function showComments(e) {
    const dreamId = e.target.dataset.dreamId;
    const dreamComments = document.querySelectorAll(".dream-comments");

    dreamComments.forEach(dreamComment => {
        if (dreamId === dreamComment.dataset.dreamId) {
            dreamComment.classList.toggle("show-comments");
        }
    });
}
    likeButtons.forEach(likeButton => {
        likeButton.addEventListener('click', likeComment)
    });


async function likeComment(e){
    const dreamId = e.target.dataset.dreamId;
    let dreamIdInt=parseInt(dreamId);
    console.log(dreamIdInt);
    const data ={'dreamId': dreamIdInt};

        const response = await fetch("/like", {
            method: "POST",
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });

        e.target.classList.toggle('liked');
}



    function updateLikeStatus(isLiked) {
        const likeIcon = document.querySelectorAll('.like');
        const unlikeIcon = document.querySelectorAll('.unlike');
        const likeCount = document.querySelectorAll('p');

        if (isLiked) {
            likeIcon.style.display = 'inline-block';
            unlikeIcon.style.display = 'none';
            likeCount.textContent = parseInt(likeCount.textContent) + 1;
        } else {
            likeIcon.style.display = 'none';
            unlikeIcon.style.display = 'inline-block';
            likeCount.textContent = parseInt(likeCount.textContent) - 1;
        }
    }

