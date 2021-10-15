import { createRouter, createWebHashHistory } from 'vue-router'
import ProductsList from '../pages/Products/ProductsListPage'
import CreateProductPage from '../pages/Products/CreateProductPage'

const routes = [
	{ path: '/', component: ProductsList },
	{ path: '/create', component: CreateProductPage },
]

const router = createRouter({
	history: createWebHashHistory(),
	routes,
})

export default router
