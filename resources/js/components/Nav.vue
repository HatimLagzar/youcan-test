<template>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark rounded">
    <div class="container">
      <div class="row">
        <div class="col-4">
          <CategoryFilter />
        </div>
        <div class="col-4">
          <SortBy />
        </div>
        <div class="col-4">
          <AddProductBtn />
        </div>
      </div>
    </div>
  </nav>
</template>

<script>
export default {
  setup: () => ({}),
};
</script>