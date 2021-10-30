function fetchCategories() {
    return fetch("/api/categories")
        .then((response) => response.json())
        .catch((error) => console.log(error));
}

function fetchProductsCategories() {
    return fetch("/api/products/categories")
        .then((response) => response.json())
        .catch((error) => console.log(error));
}

module.exports = {fetchCategories, fetchProductsCategories}
