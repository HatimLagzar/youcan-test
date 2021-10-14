<template>
	<h1 class="text-center mb-5">Products List</h1>
	<div class="container">
		<div class="row">
			<ProductSummary v-for="product in products" :product="product"></ProductSummary>
		</div>
	</div>
</template>

<script>
export default {
	setup: () => ({}),
	data: () => {
		return {
			products: []
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
						this.products = [...response.products]
					} else {
						toastr.error(response.msg)
					}
				})
			.catch(error => console.log(error))
		}
	}
}
</script>
