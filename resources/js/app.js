
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */


require('./bootstrap');

import Vue from 'vue';
import Vuetify from 'vuetify';
import colors from 'vuetify/es5/util/colors';
import 'vuetify/dist/vuetify.min.css';
import 'material-design-icons-iconfont/dist/material-design-icons.css';
import router from './router';
window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */
Vue.component('example-component', example_component)
Vue.component('admin-component', admin_component)
Vue.component('r-link', r_link)

//
import home from '../components/HomeComponent.vue'
import admin_user from '../components/Admin/UserComponent.vue'
Vue.use(Vuetify, {
    theme: {
        primary: colors.indigo.base,
        secondary: colors.blue.base,
        accent: colors.amber.base,
    }
});

export default new Router({
    mode: 'history',
    routes: [
        { path: '/', name: 'home', component: home, meta: { name: 'ホーム', icon: 'home' } },
        { path: '/admin/user', name: 'admin_user', component: admin_user, meta: { name: '社員管理', icon: 'supervisor_account' } },
    ],
})
// const files = require.context('./', true, /\.vue$/i)

// files.keys().map(key => {
//     return Vue.component(_.last(key.split('/')).split('.')[0], files(key))
// })

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    router,
});
