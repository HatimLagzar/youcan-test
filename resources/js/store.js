import {reactive} from "vue";

export default {
	state: reactive({
		products: [],
		sortBy: 'NAME'
	}),
}
