
document.getElementById('logoutButton').addEventListener('click', function() {
    var confirmLogout = confirm('Are you sure you want to logout?');
    if (confirmLogout) {
        // Redirect to another page
        window.location.href = "main.html"; // Replace 'logout.php' with the actual URL you want to redirect to
    }
});

// Function to fetch JSON data from a file
async function fetchJsonFile(url) {
    const response = await fetch(url);
    const data = await response.json();
    return data;
}

// Populate the categories dropdown from the JSON file
const categoryDropdown = document.getElementById("category");
fetchJsonFile('products.json')
    .then(data => {
        data.forEach(category => {
            const option = document.createElement("option");
            option.value = category.category;
            option.textContent = category.category;
            categoryDropdown.appendChild(option);
        });
    })
    .catch(error => console.error('Error fetching JSON:', error));

// Function to populate the items dropdown based on the selected category
function populateItems() {
    const selectedCategory = document.getElementById("category").value;
    const itemsDropdown = document.getElementById("item");

    // Clear existing options
    itemsDropdown.innerHTML = '';

    // Find the selected category in the data array
    fetchJsonFile('products.json')
        .then(data => {
            const selectedCategoryData = data.find(category => category.category === selectedCategory);

            // Populate items if the category is found
            if (selectedCategoryData) {
                selectedCategoryData.items.forEach(item => {
                    const option = document.createElement("option");
                    option.value = item;
                    option.textContent = item;
                    itemsDropdown.appendChild(option);
                });
            }
        })
        .catch(error => console.error('Error fetching JSON:', error));
}
