import Vue from "vue";
import VueRouter from "vue-router";

Vue.use(VueRouter)

export default new VueRouter({
    mode: 'history',

    routes: [
        {
            path: '/admin/users',
            component: () => import('./components/User/Index'),
            name: 'user.index',
        },
        {
            path: '/admin/users/create',
            component: () => import('./components/User/Create'),
            name: 'user.create',
        },
        {
            path: '/admin/users/edit/:user',
            component: () => import('./components/User/Edit'),
            name: 'user.edit',
        },
        {
            path: '/admin/users/show/:user',
            component: () => import('./components/User/Show'),
            name: 'user.show',
        },
        {
            path: '/admin/files',
            component: () => import('./components/File/Index'),
            name: 'file.index',
        },
        {
            path: '/admin/files/create',
            component: () => import('./components/File/Create'),
            name: 'file.create',
        },
        {
            path: '/admin/files/show/:file',
            component: () => import('./components/File/Show'),
            name: 'file.show',
        },
    ]
})
