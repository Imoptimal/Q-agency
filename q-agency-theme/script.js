document.addEventListener("DOMContentLoaded", function() {
    var currentUrl = window.location;
    var allMovies;
    var titleEl = document.querySelector('.qagency-content .title');
    // Get the data for all posts
    fetch('/wp-json/wp/v2/qagencymovies/')
        .then(response => response.json())
        .then(data => allMovies = data)
        .then(() => allMovies.forEach(movie => {
            // Get the title for the current post
            if (currentUrl == movie.link) {
                var currentPostTitle = movie.qagencymovies_meta_key['_qagencymovies_meta_key'][0];
                titleEl.textContent = currentPostTitle;
            }
        }))
        .catch(err => console.error(err));
});