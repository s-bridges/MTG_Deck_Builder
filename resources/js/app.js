
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import { VLazyImagePlugin } from "v-lazy-image";
import VuePaginate from 'vue-paginate';
import Ads from 'vue-google-adsense';

Vue.use(VLazyImagePlugin);
Vue.use(VuePaginate);
Vue.use(require('vue-script2'));
Vue.use(Ads.Adsense);
Vue.use(Ads.InArticleAdsense);
Vue.use(Ads.InFeedAdsense);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example-component', require('./components/ExampleComponent.vue'));
Vue.component('list-cards', require('./components/ListCards.vue'));
Vue.component('show-decks', require('./components/ShowDecks.vue'));
Vue.component('deck-cards', require('./components/DeckCards.vue'));
Vue.component('deck-of-the-week', require('./components/DeckOfTheWeek.vue'));
Vue.component('admin-page', require('./components/AdminPage.vue'));
Vue.component('user-page', require('./components/UserPage.vue'));
Vue.component('health-page', require('./components/HealthPage.vue'));
Vue.component('all-decks', require('./components/AllDecks.vue'));
Vue.component('all-blogs', require('./components/AllBlogs.vue'));
Vue.component('power-levels', require('./components/PowerLevels.vue'));
Vue.component('editor-page', require('./components/EditorPage.vue'));

const app = new Vue({
    el: '#app'
});