import { createApp } from 'vue'
import App from './components/App.vue';
import Nav from './components/Nav.vue';
import CategoryFilter from './components/CategoryFilter.vue';
import SortBy from './components/SortBy.vue';
import AddProductBtn from './components/AddProductBtn.vue';

const app = createApp({});
app.component('App', App)
  .component('Nav', Nav)
  .component('CategoryFilter', CategoryFilter)
  .component('SortBy', SortBy)
  .component('AddProductBtn', AddProductBtn)
  .mount('#root');

require('./bootstrap');