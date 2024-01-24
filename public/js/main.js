const commentIcons = document.querySelectorAll(".comment_icon");
const likeButtons = document.querySelectorAll('.likes');


updateLikeStatus();
commentIcons.forEach(commentIcon => {
    commentIcon.addEventListener('click', showComments);
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
    likeButton.addEventListener('click', async function () {
        const dreamId = likeButton.dataset.dreamId;
        let dreamIdInt = parseInt(dreamId);

        const data = {'dreamId': dreamIdInt};

        const response = await fetch("/like", {
            method: "POST",
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });

        try {
            const response = await fetch("/isLiked", {
                method: "GET",
            });

            if (response.status !== 200) throw new Error('Error');

            const likedDreams = await response.json();

            const dreamId_number = parseInt(dreamId);
            let isLiked = likedDreams.includes(dreamId_number);

            const likeIcon = likeButton.querySelector("i");
            likeIcon.classList.toggle('liked', isLiked);

            const likeCount = likeButton.querySelector('.like-amount');

            if (isLiked) {
                likeCount.textContent = parseInt(likeCount.textContent) + 1;
            } else {
                likeCount.textContent = parseInt(likeCount.textContent) - 1;
            }

        } catch (error) {
            console.error('Error from fetch API:', error);
        }
    })
});

async function updateLikeStatus() {
    try {
        const response = await fetch("/isLiked", {
            method: "GET",
        });

        if (response.status !== 200) throw new Error('Error');

        const likedDreams = await response.json();

        likeButtons.forEach((likeButton) => {
            const dreamId = parseInt(likeButton.dataset.dreamId);

            let isLiked = likedDreams.includes(dreamId);

            const likeIcon = likeButton.querySelector("i");

            likeIcon.classList.toggle('liked', isLiked);

        });
    } catch (error) {
        console.error('Error from fetch API:', error);
    }
}

async function likeDream(e) {
    const dreamId = e.target.dataset.dreamId;
    let dreamIdInt = parseInt(dreamId);

    const data = {'dreamId': dreamIdInt};

    const response = await fetch("/like", {
        method: "POST",
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    });

    try {
        const response = await fetch("/isLiked", {
            method: "GET",
        });

        if (response.status !== 200) throw new Error('Error');

        const likedDreams = await response.json();

        let isLiked = likedDreams.includes(dreamId);

        const likeIcon = e.target.querySelector("i");
        console.log(e.target);
        e.target.classList.toggle('liked', isLiked);

        const likeCount = e.target.querySelector('.like-amount');
        console.log(likeCount.textContent);
        if (isLiked) {
            likeCount.textContent = parseInt(likeCount.textContent) + 1;
        } else {
            likeCount.textContent = parseInt(likeCount.textContent) - 1;
        }

    } catch (error) {
        console.error('Error from fetch API:', error);
    }

}





