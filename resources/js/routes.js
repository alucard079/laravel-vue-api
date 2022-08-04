import Login from './pages/Login';
import Dashboard from './pages/Dashboard';

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
    }
];