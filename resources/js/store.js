import {reactive} from "vue";

export default {
	state: reactive({
		categories: [],
		products: [],
		filteredProducts: [],
		sortBy: 'NAME',
		selectedCategory: ''
	}),
}
