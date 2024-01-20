document.addEventListener('DOMContentLoaded', function () {
    // Get the user info and statistics divs
    const userInfoDiv = document.querySelector('.other > div:nth-child(1)');
    const statisticsDiv = document.querySelector('.other > div:nth-child(3)');

    // Add click event listeners to the divs
    userInfoDiv.addEventListener('click', function () {
        toggleSection('.profile-info');
    });

    statisticsDiv.addEventListener('click', function () {
        toggleSection('.statistics-info');
    });

    // Function to toggle the visibility or height of the specified section
    function toggleSection(sectionSelector) {
        let section = document.querySelector(sectionSelector);
        section.classList.toggle('hidden');

        // Update the max-height property when adding/removing the 'hidden' class
        section.style.maxHeight = section.classList.contains('hidden') ? null : section.scrollHeight + "px";
    }
});
