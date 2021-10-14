<template>
	<div class="col-4 mb-4">
		<div class="card h-100">
			<div class="card-header">
				<img
					class="w-100 img-thumbnail"
					v-bind:src="'storage/products/' + product.image_src"
					v-bind:alt="product.name"
				/>
			</div>
			<div class="card-body">
				<h4>{{ product.name }}</h4>
				<p>{{ product.description }}</p>
				<strong>USD {{ parseInt(product.price) / 100 }}</strong>
			</div>
		</div>
	</div>
</template>

<script>
export default {
	props: ['product'],
	setup: () => ({}),
}
</script>
