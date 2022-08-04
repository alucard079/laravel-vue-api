import Login from './pages/Login';
import Dashboard from './pages/Dashboard';
import Permissions from './pages/permissions/Index';
import CreatePermission from './pages/permissions/Create';
import EditPermission from './pages/permissions/Edit';

export default [
    {
        path: '/',
        component: Login,
        name: 'login',
        meta: { requiresAuth: false },
    },
    {
        path: '/dashboard',
        component: Dashboard,
        name: 'dashboard',
        meta: { requiresAuth: true },
    },
    {
        path: '/permissions',
        component: Permissions,
        name: 'permissions',
        meta: { requiresAuth: true },
    },
    {
        path: '/permissions/create',
        component: CreatePermission,
        name: 'permissions-create',
        meta: { requiresAuth: true },
    },
    {
        path: '/permissions/:id/edit',
        component: EditPermission,
        name: 'permissions-edit',
        meta: { requiresAuth: true },
    }
];