document.addEventListener('DOMContentLoaded', function () {
    const userInfoDiv = document.querySelector('.other > div:nth-child(1)');
    const statisticsDiv = document.querySelector('.other > div:nth-child(3)');

    // Add click event listeners to the divs
    userInfoDiv.addEventListener('click', function () {
        toggleSection('.profile-info');
    });

    statisticsDiv.addEventListener('click', function () {
        toggleSection('.statistics-info');
    });

    function toggleSection(sectionSelector) {
        let section = document.querySelector(sectionSelector);
        section.classList.toggle('hidden');

        section.style.maxHeight = section.classList.contains('hidden') ? null : section.scrollHeight + "px";
    }
});

const deleteUser = async email => {
    const formData = new FormData();
    formData.append('email', email);

    const response = await fetch('/delete_user', {
        method: 'POST',
        body: formData
    });

    if (response.status !== 200) throw new Error('Error');

    return true;
};
document.querySelectorAll('.small-btn').forEach(btn => {
    btn.addEventListener('click', e => {
        if (!confirm("Are you sure?")) return;
        if (!!e?.target.dataset?.email) {
            deleteUser(e.target.dataset.email)
                .then(res => {
                    alert('User has been removed!');
                })
                .catch(e => {
                    alert('Error! Try again.');
                });
        }
    });
});