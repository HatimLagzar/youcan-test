<template>
  <div class="form-group">
    <label for="category">Category</label>
    <select
      id="category"
      class="form-select w-auto d-inline-block ms-2 bg-transparent border-0"
      @change="handleCategoryChange"
    >
      <option value="">All</option>
      <option
        v-for="category in categories"
        v-bind:key="category.id"
        v-bind:value="category.id"
        v-bind:selected="selectedCategory === category.id"
      >
        {{ category.name }}
      </option>
    </select>
  </div>
</template>

<script>
import store from "../store";

export default {
  setup: () => ({}),
  data: () => {
    return {
      categories: [],
      selectedCategory: store.state.selectedCategory,
    };
  },
  mounted() {
    this.getCategories();
  },
  methods: {
    getCategories() {
      return fetch("/api/categories")
        .then((response) => response.json())
        .then((response) => {
          this.categories = response.categories;
        })
        .catch((error) => console.log(error));
    },

    handleCategoryChange(e) {
      e.preventDefault();
      store.state.selectedCategory = e.currentTarget.value;
      if (store.state.selectedCategory) {
        store.state.filteredProducts = store.state.products.filter((item) => {
          const categoryFound = item.categories.find(
            (category) =>
              parseInt(category.category_id) ===
              parseInt(store.state.selectedCategory)
          );

          if (categoryFound) {
            return item;
          }
        });
      } else {
        store.state.filteredProducts = store.state.products;
      }

      // store.state.products = store.state.products.filter()
    },
  },
};
</script>
