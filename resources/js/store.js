import {reactive} from "vue";

export default {
	state: reactive({
		products: [],
		filteredProducts: [],
		sortBy: 'NAME',
		selectedCategory: ''
	}),
}
