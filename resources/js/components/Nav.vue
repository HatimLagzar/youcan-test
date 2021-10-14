<template>
  <nav class="navbar navbar-expand-lg navbar-light bg-light rounded">
    <div class="container">
      <div class="row w-100">
        <div class="col-4">
          <CategoryFilter />
        </div>
        <div class="col-4">
          <SortBy />
        </div>
        <div class="col-4 align-self-center">
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
