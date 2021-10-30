<template>
	<h1 class="text-center mb-5">Products List</h1>
	<div class="container">
		<div class="row">
			<ProductSummary
				v-for="product in sortedProducts"
				v-bind:key="product.id"
				:product="product"
			></ProductSummary>
		</div>

		<nav class="d-flex justify-content-center">
			<ul class="pagination mt-5">
				<li
					v-for="link in getPaginationLinks"
					:class="'page-item ' + (!link.url ? 'disabled' : '')"
					v-bind:key="link.label"
				>
					<button
						class="page-link"
						:data-url-id="link.id"
						v-html="link.label"
						@click="handlePaginationClick"
					></button>
				</li>
			</ul>
		</nav>
	</div>
</template>

<script>
import store from '../../store'
import {fetchProductsCategories} from "../../api/categories";

export default {
    setup: () => ({}),

    data: () => {
        return {
            products: store.state.filteredProducts,
            pages: [],
        }
    },

    mounted() {
        this.getProductsCategories()
            .then(() => {
                this.getProducts()
            })
    },

    methods: {
        getProducts(page = 1) {
            fetch('/api/products?page=' + page)
                .then((response) => response.json())
                .then((response) => {
                    if (response.status === 200) {
                        let products = response.products
                        products.data = products.data.map(product => {
                            product.categories = store.state.productsCategories
                                .filter(category => parseInt(category.product_id) === parseInt(product.id))

                            return product
                        })

                        store.state.products = products
                        store.state.filteredProducts = products.data
                        $('select#category').val('')
                        store.state.selectedCategory = ''
                        $('select#sort-by').val('NAME')
                        store.state.sortBy = 'NAME'
                    } else {
                        toastr.error(response.msg)
                    }
                })
                .catch((error) => console.log(error))
        },

        getProductsCategories() {
            return fetchProductsCategories()
                .then(response => {
                    if (response.status === 200) {
                        store.state.productsCategories = response.productsCategories
                    } else {
                        toastr.error(response.msg || "Couldn't get products categories")
                    }
                })
        },

        handlePaginationClick(e) {
            e.preventDefault()
            const pageId = e.currentTarget.getAttribute('data-url-id')
            if (pageId) {
                const page = this.pages.find((page) => page.id === pageId)
                if (page) {
                    const pageNumber = page.url
                        .split('?')[1]
                        .split('&')
                        .map((item) => item.split('='))
                        .find((item) => item[0] == 'page')[1]

                    this.getProducts(parseInt(pageNumber))
                }
            }
        },

        randomBytes(length) {
            let result = ''
			let characters =
				'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'
			let charactersLength = characters.length
			for (let i = 0; i < length; i++) {
				result += characters.charAt(
					Math.floor(Math.random() * charactersLength)
				)
			}
			return result
		},
	},

	computed: {
		sortedProducts() {
			if (store.state.sortBy === 'NAME') {
				this.products = store.state.filteredProducts.sort((a, b) =>
					a.name.localeCompare(b.name)
				)
			} else {
				this.products = store.state.filteredProducts.sort(
					(a, b) => a.price - b.price
				)
			}

			return this.products
		},

		getPaginationLinks() {
			this.pages = []
			if (store.state.products.links) {
				store.state.products.links.forEach((link) => {
					this.pages.push({
						id: this.randomBytes(10),
						label: link.label,
						url: link.url,
					})
				})
			}
			return this.pages
		},
	},
}
</script>
