document.getElementById('logoutButton').addEventListener('click', function () {
    var confirmLogout = confirm('Are you sure you want to logout?');
    if (confirmLogout) {
        // Redirect to another page
        window.location.href = "main.html"; // Replace 'logout.php' with the actual URL you want to redirect to
    }
});

document.addEventListener('DOMContentLoaded', function () {
    let data; // Declare data in a higher scope
    let itemDropdown; // Declare itemDropdown in a higher scope

    function fetchData(url) {
        return fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .catch(error => console.error('Fetch Error:', error));
    }

    function populateDropdown(dropdown, items) {
        dropdown.innerHTML = '';
        if (items && items.length) {
            items.forEach(item => {
                const option = document.createElement('option');
                option.value = item;
                option.text = item;
                dropdown.appendChild(option);
            });
        } else {
            // Handle the case where items are undefined or empty
            console.error('Items data is undefined or empty');
        }
    }

    const categoryDropdown = document.getElementById('category');

    // Fetch data from the server
    fetchData('fetch_data.php')
        .then(responseData => {
            data = responseData; // Assign the response to the higher-scoped data variable

            // Populate category dropdown
            populateDropdown(categoryDropdown, data.categories);

            // Populate items for the first category
            itemDropdown = document.getElementById('item'); // Initialize itemDropdown
            const initialCategory = data.categories[0];

            if (initialCategory && data.items && data.items[initialCategory]) {
                populateDropdown(itemDropdown, data.items[initialCategory]);
            } else {
                console.error('Initial category or items data is missing or empty');
            }

            

        });

    // Update items when category changes
    categoryDropdown.addEventListener('change', function () {
        const selectedCategory = this.value;

        // Ensure data is defined before using it
        if (data && data.items && data.items[selectedCategory]) {
            // Populate items for the selected category
            populateDropdown(itemDropdown, data.items[selectedCategory]);
        } else {
            console.error(`Data or items for category ${selectedCategory} is missing or empty`);
        }
    });
});
