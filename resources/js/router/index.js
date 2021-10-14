import {createRouter, createWebHashHistory} from 'vue-router'
import ProductsList from "../pages/Products/ProductsList";
import CreateProductPage from "../pages/Products/Create";

const routes = [
	{ path: '/', component: ProductsList },
	{ path: '/create', component: CreateProductPage },
]

const router = createRouter({
	history: createWebHashHistory(),
	routes
})

export default router
