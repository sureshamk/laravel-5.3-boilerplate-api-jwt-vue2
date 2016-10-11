/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */
require('./bootstrap');

/*
 * vue router inclusion
 * */
import VueRouter from "vue-router";
import auth from "./auth";
import App from "./components/App.vue";
import Container from "./components/Container.vue";
import About from "./components/About.vue";
import Dashboard from "./components/Dashboard.vue";
import Login from "./components/Auth/Login.vue";
import Register from "./components/Auth/Register.vue";
import ForgotPassword from "./components/Auth/ForgotPassword.vue";
import Users from "./components/Users.vue";



var VueResource = require('vue-resource');
var VueTables = require('vue-tables-2');

/*
 * import
 * components
 * */


Vue.use(VueResource);
Vue.use(VueRouter);
Vue.use(VueTables.client);
Vue.use(VueTables.server);

/*
 * components
 * */
const Default = {template: '<div>default</div>'}
const Foo = {template: '<div>foo</div>'}
const Bar = {template: '<div>bar</div>'}
const Baz = {template: '<div>baz</div>'}


const router = new VueRouter({
    mode: 'hash',
    base: __dirname,
    routes: [
        {
            path: '/',
            component: Container,
            meta: {requiresAuth: true},
            children: [
                // an empty path will be treated as the default, e.g.
                // components rendered at /parent: Root -> Parent -> Default
                {path: '', component: Dashboard},

                // components rendered at /parent/foo: Root -> Parent -> Foo
                {path: 'users', component: Users},

                // components rendered at /parent/bar: Root -> Parent -> Bar
                {path: 'bar', component: Bar},

                // NOTE absolute path here!
                // this allows you to leverage the component nesting without being
                // limited to the nested URL.
                // components rendered at /baz: Root -> Parent -> Baz
                {path: '/baz', component: Baz}
            ]
        },

        {
            path: '/about',
            component: About,
            meta: {requiresAuth: true},
            children: [
                // an empty path will be treated as the default, e.g.
                // components rendered at /parent: Root -> Parent -> Default
                {path: '', component: Dashboard},

                // components rendered at /parent/foo: Root -> Parent -> Foo
                {path: 'foo', component: Foo},

                // components rendered at /parent/bar: Root -> Parent -> Bar
                {path: 'bar', component: Bar},

                // NOTE absolute path here!
                // this allows you to leverage the component nesting without being
                // limited to the nested URL.
                // components rendered at /baz: Root -> Parent -> Baz
                {path: '/baz', component: Baz}
            ]
        },
        {
            path: '/login',
            component: Login
        },
        {
            path: '/forgot-password',
            component: ForgotPassword
        },
        {
            path: '/register',
            component: Register
        },
        {
            path: '/logout',
            beforeEnter (to, from, next) {
                auth.logout();
                next('/login')
            }
        }
    ]
});
router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        // this route requires auth, check if logged in
        // if not, redirect to login page.
        if (!auth.loggedIn()) {
            next({
                path: '/login',
                query: {redirect: to.fullPath}
            })
        } else {
            next()
        }
    } else {
        next(); // make sure to always call next()!
    }
});


// 4. Create and mount the root instance.
// Make sure to inject the router with the router option to make the
// whole app router-aware.

new Vue(Vue.util.extend({router}, App)).$mount('#app');