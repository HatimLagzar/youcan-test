<template>
	<h1>Create Product</h1>

	<form @submit="handleCreateProduct">
		<div class="form-group mb-2">
			<label for="name">Name</label>
			<input name="name" id="name" type="text" class="form-control"/>
		</div>

		<div class="form-group mb-2">
			<label for="price">Price</label>
			<input name="price" id="price" type="text" class="form-control"/>
		</div>

		<div class="form-group mb-2">
			<label for="description">Description</label>
			<textarea
				name="description"
				id="description"
				class="form-control"
			></textarea>
		</div>

		<div class="form-group mb-2">
			<label for="description" class="form-label">Product Thumbnail</label>
			<input
				name="image"
				class="form-control"
				type="file"
				id="description"
				accept="image/*"
			/>
		</div>

		<div class="form-group mb-2">
			<button type="submit" class="btn btn-primary me-2">Validate</button>
			<button type="reset" class="btn btn-light">Clear Fields</button>
		</div>
	</form>
</template>

<script>
export default {
	setup: () => ({}),

	methods: {
		handleCreateProduct(e) {
			e.preventDefault();
			const formNode = e.target
			const formData = new FormData(formNode);
			fetch(
				"/api/products",
				{
					method: "POST",
					body: formData
				}
			)
				.then((response) => response.json())
				.then((response) => {
					if (response.status === 200) {
						formNode.reset()
						toastr.success(response.msg);
						this.$router.push({path: '/'})
					} else {
						toastr.error(response.msg);
					}
				});
		},
	},
};
</script>
