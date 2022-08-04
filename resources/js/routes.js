import Login from './pages/Login';
import Register from './pages/Register';
import Verify from './pages/Verify';
import Dashboard from './pages/Dashboard';
import Permissions from './pages/permissions/Index';
import CreatePermission from './pages/permissions/Create';
import EditPermission from './pages/permissions/Edit';
import Roles from './pages/roles/Index';
import CreateRole from './pages/roles/Create';
import EditRole from './pages/roles/Edit';
import Users from './pages/users/Index';
import CreateUser from './pages/users/Create';
import EditUser from './pages/users/Edit';

export default [
    {
        path: '/',
        component: Login,
        name: 'login',
        meta: { requiresAuth: false },
    },
    {
        path: '/register',
        component: Register,
        name: 'register',
        meta: { requiresAuth: false },
    },
    {
        path: '/verify/:email',
        component: Verify,
        name: 'verify',
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
    },
    {
        path: '/roles',
        component: Roles,
        name: 'roles',
        meta: { requiresAuth: true },
    },
    {
        path: '/roles/create',
        component: CreateRole,
        name: 'roles-create',
        meta: { requiresAuth: true },
    },
    {
        path: '/roles/:id/edit',
        component: EditRole,
        name: 'roles-edit',
        meta: { requiresAuth: true },
    },
    {
        path: '/users',
        component: Users,
        name: 'users',
        meta: { requiresAuth: true },
    },
    {
        path: '/users/create',
        component: CreateUser,
        name: 'users-create',
        meta: { requiresAuth: true },
    },
    {
        path: '/users/:id/edit',
        component: EditUser,
        name: 'users-edit',
        meta: { requiresAuth: true },
    },
];