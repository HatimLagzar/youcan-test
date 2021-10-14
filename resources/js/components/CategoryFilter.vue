<template>
	<div class="form-group">
		<label for="category">Category</label>
		<select class="form-select w-auto d-inline-block ms-2 bg-transparent border-0" id="category">
			<option value="">All</option>
			<option v-for="category in categories" value="{{ category.id }}">{{ category.name }}</option>
		</select>
	</div>
</template>

<script>
export default {
	setup: () => ({}),
	data: () => {
		return {
			categories: []
		}
	},
	mounted() {
		this.getCategories()
	},
	methods: {
		getCategories() {
			return fetch('/api/categories')
				.then(response => response.json())
				.then(response => {
					console.log(this)
					this.categories = response.categories
				})
				.catch(error => console.log(error))
		}
	}
};
</script>
