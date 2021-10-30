import {reactive} from 'vue'

export default {
    state: reactive({
        productsCategories: [],
        categories: [],
        products: [],
        filteredProducts: [],
        sortBy: 'NAME',
        selectedCategory: '',
    }),
}
