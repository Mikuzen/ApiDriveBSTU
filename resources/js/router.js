import Vue from "vue";
import VueRouter from "vue-router";

Vue.use(VueRouter)

export default new VueRouter({
    mode: 'history',

    routes: [
        {
            path: '/users',
            component: () => import('./components/User/Index'),
            name: 'user.index',
        },
        {
            path: '/users/create',
            component: () => import('./components/User/Create'),
            name: 'user.create',
        },
        {
            path: '/users/edit/:user',
            component: () => import('./components/User/Edit'),
            name: 'user.edit',
        },
        {
            path: '/users/show/:user',
            component: () => import('./components/User/Show'),
            name: 'user.show',
        },
    ]
})
