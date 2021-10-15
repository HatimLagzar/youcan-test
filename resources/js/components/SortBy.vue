<template>
	<div class="form-group">
		<label for="sort-by">Sort By</label>
		<select
			id="sort-by"
			class="form-select w-auto d-inline-block ms-2 bg-transparent border-0"
			@change="handleSortByChange"
		>
			<option value="NAME" v-bind:selected="sortedBy === 'NAME'">Alphabetical Order</option>
			<option value="PRICE" v-bind:selected="sortedBy === 'PRICE'">Lowest First</option>
		</select>
	</div>
</template>

<script>
import store from "../store";

export default {
	setup: () => ({}),
	data: () => {
		return {
			sortedBy: store.state.sortBy
		}
	},
	methods: {
		handleSortByChange(e) {
			e.preventDefault()
			store.state.sortBy = e.currentTarget.value
		}
	}
};
</script>
