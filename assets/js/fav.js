const hearts = document.querySelectorAll('.fav');

hearts.forEach(
    function(heart) {
        heart.addEventListener('click', addToFav);
    }
)

function addToFav(event) {

// Get the link object you click in the DOM
    let favImg = event.target;
    let link = favImg.dataset.text;
// Send an HTTP request with fetch to the URI defined in the href
    fetch(link)
        // Extract the JSON from the response
        .then(res => res.json())
        // Then update the icon
        .then(function(res) {
            if (res.isInFav) {
                favImg.classList.remove('far'); // Remove the .far (empty heart) from classes in <i> element
                favImg.classList.add('fas'); // Add the .fas (full heart) from classes in <i> element
            } else {
                favImg.classList.remove('fas'); // Remove the .fas (full heart) from classes in <i> element
                favImg.classList.add('far');
            }
        });
}