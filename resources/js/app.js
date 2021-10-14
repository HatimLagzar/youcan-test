require('./bootstrap');
import { createApp } from 'vue'
import App from './components/App.vue';
import Nav from './components/Nav.vue';
import CategoryFilter from './components/CategoryFilter.vue';
import SortBy from './components/SortBy.vue';
import AddProductBtn from './components/AddProductBtn.vue';
import ProductsListBtn from './components/ProductsListBtn.vue';
import ProductsList from './pages/Products/ProductsListPage.vue';
import CreateProductPage from './pages/Products/CreateProductPage.vue';
import router from './router'

const app = createApp(App)

app.component('App', App)
app.component('Nav', Nav)
app.component('CategoryFilter', CategoryFilter)
app.component('SortBy', SortBy)
app.component('AddProductBtn', AddProductBtn)
app.component('ProductsListBtn', ProductsListBtn)
app.component('ProductsList', ProductsList)
app.component('CreateProductPage', CreateProductPage)

app.use(router)
app.mount('#root')
