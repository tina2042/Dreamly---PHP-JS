const searchBar = document.querySelector('.searchTerm');
const searchResults = document.querySelector('#search-results');
const searchButton = document.querySelector('.searchButton');

async function addFriend(user_id) {
    const formData = new FormData();
    formData.append('user_id', user_id);

    try {
        const response = await fetch("/add_friend", {
            method: "POST",
            body: formData
        });
        if (response.status !== 200) throw new Error('Error');
        alert("Friend added");
    } catch (error) {
        console.error('Error from fetch API:', error);
    }
}

async function searchFriends(query, clicked) {
    searchResults.innerHTML = '';
    searchResults.style.display = 'none';

    if (query.length >= 3 || clicked) {
        const formData = new FormData();
        formData.append('query', query);

        try {
            const response = await fetch("/search", {
                method: "POST",
                body: formData
            });
            if (response.status !== 200) throw new Error('Error');
            const data = await response.json();
            searchResults.style.display = 'block';
            if (data.length === 0) {
                const listItem = document.createElement('li');
                listItem.textContent = "User not found";

                searchResults.appendChild(listItem);
            } else {
                data.forEach(user => {
                    const listItem = document.createElement('li');

                    listItem.innerHTML = `
                        <div class="search-result-item">
                            <img src="${user.photo}" alt="${user.full_name}">
                            <span>${user.full_name}</span>
                        </div>
                    `;
                    listItem.addEventListener('click', () => {
                        addFriend(user.user_id);
                        searchBar.value = '';
                        searchResults.style.display = 'none';
                    });
                    searchResults.appendChild(listItem);
                });
            }
        } catch (error) {
            console.error('Error from fetch API:', error);
        }
    }
}

let searchTimeout = false;
searchBar.addEventListener('input', function () {
    if (searchTimeout) clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        searchFriends(this.value, false);
    }, 250);
});
searchButton.addEventListener('click', function () {
    if (searchTimeout) clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        searchFriends(searchBar.value, true);
    }, 250);
});