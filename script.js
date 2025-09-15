function fetchResults(filter) {
    const searchQuery = document.getElementById('searchInput').value;
    const searchResultsDiv = document.getElementById('searchResults');

    // Create the URL for the search query and filter
    const url = `search.php?filter=${filter}&searchQuery=${encodeURIComponent(searchQuery)}`;

    // Use fetch to call the search.php file
    fetch(url)
        .then(response => response.text())
        .then(data => {
            // Insert the fetched data into the results div
            searchResultsDiv.innerHTML = data;
            searchResultsDiv.style.display = 'block';  // Show the results div
        })
        .catch(error => {
            console.error('Error fetching data:', error);
            searchResultsDiv.innerHTML = '<p>Sorry, an error occurred while fetching the results.</p>';
        });
}
