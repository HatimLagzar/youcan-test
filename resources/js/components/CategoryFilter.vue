<template>
	<div class="form-group">
		<label for="category">Category</label>
		<select
			id="category"
			class="form-select w-auto d-inline-block ms-2 bg-transparent border-0"
			@change="handleCategoryChange"
		>
			<option v-bind:selected="getSelectedCategory === ''" value="">All</option>
			<option
				v-for="category in getCategories"
				v-bind:key="category.id"
				v-bind:value="category.id"
				v-bind:selected="getSelectedCategory === category.id"
			>
				{{ category.name }}
			</option>
		</select>
	</div>
</template>

<script>
import store from '../store'

export default {
	setup: () => ({}),

	data: () => {
		return {
			categories: store.state.categories,
			selectedCategory: store.state.selectedCategory,
		}
	},

	mounted() {
		this.pullCategories()
	},

	methods: {
		pullCategories() {
			const { fetchCategories } = require('./../api/categories')
			fetchCategories().then((response) => {
				store.state.categories = response.categories
			})
		},

		handleCategoryChange(e) {
			e.preventDefault()
			store.state.selectedCategory = e.currentTarget.value
			if (store.state.selectedCategory) {
				store.state.filteredProducts = store.state.products.data.filter(
					(item) => {
						const categoryFound = item.categories.find(
							(category) =>
								parseInt(category.category_id) === parseInt(store.state.selectedCategory)
						)

						if (categoryFound) {
							return item
						}
					}
				)
			} else {
				store.state.filteredProducts = store.state.products.data
			}
		},
	},

	computed: {
		getCategories() {
			return store.state.categories
		},

		getSelectedCategory() {
			return store.state.selectedCategory
		},
	},
}
</script>
