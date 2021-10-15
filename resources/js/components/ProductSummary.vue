<template>
	<div class="col-4 mb-4">
		<div class="card h-100">
			<div class="card-header">
				<img
					class="w-100 img-thumbnail"
					v-bind:src="
						isValidURL(product.image_src) 
							? product.image_src 
							: 'storage/products/' + product.image_src
					"
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
	methods: {
		isValidURL(string) {
			let url;
			
			try {
				url = new URL(string);
			} catch (_) {
				return false;  
			}

			return url.protocol === "http:" || url.protocol === "https:";
		}
	}
}
</script>
