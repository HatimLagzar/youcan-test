<template>
	<h1 class="text-center mb-5">Products List</h1>
	<div class="container">
		<div class="row">
			<ProductSummary v-for="product in sortedProducts" :product="product"></ProductSummary>
		</div>
	</div>
</template>

<script>
import store from "../../store";

export default {
	setup: () => ({}),
	data: () => {
		return {
			products: store.state.products
		}
	},
	mounted() {
		this.getProducts()
	},

	methods: {
		getProducts() {
			fetch('/api/products')
				.then(response => response.json())
				.then(response => {
					if (response.status === 200) {
						store.state.products = response.products
					} else {
						toastr.error(response.msg)
					}
				})
			.catch(error => console.log(error))
		}
	},

	computed: {
		sortedProducts() {
			if (store.state.sortBy === 'NAME') {
				this.products = store.state.products
					.sort(((a, b) => a.name.localeCompare(b.name)))
			}
			else {
				this.products = store.state.products
					.sort(((a, b) => a.price - b.price))
			}

			return this.products
		}
	}
}
</script>
