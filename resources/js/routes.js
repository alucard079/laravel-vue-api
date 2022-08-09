import Login from "./pages/LoginPage";
import Register from "./pages/RegisterPage";
import Verify from "./pages/VerifyPage";
import Dashboard from "./pages/DashBoard";
import Permissions from "./pages/permissions/IndexPage";
import CreatePermission from "./pages/permissions/CreatePage";
import EditPermission from "./pages/permissions/EditPage";
import Roles from "./pages/roles/IndexPage";
import CreateRole from "./pages/roles/CreatePage";
import EditRole from "./pages/roles/EditPage";
import Users from "./pages/users/IndexPage";
import CreateUser from "./pages/users/CreatePage";
import EditUser from "./pages/users/EditPage";

export default [
    {
        path: "/",
        component: Login,
        name: "login",
        meta: { requiresAuth: false },
    },
    {
        path: "/register",
        component: Register,
        name: "register",
        meta: { requiresAuth: false },
    },
    {
        path: "/verify/:email",
        component: Verify,
        name: "verify",
        meta: { requiresAuth: false },
    },
    {
        path: "/dashboard",
        component: Dashboard,
        name: "dashboard",
        meta: { requiresAuth: true },
    },
    {
        path: "/permissions",
        component: Permissions,
        name: "permissions",
        meta: { requiresAuth: true },
    },
    {
        path: "/permissions/create",
        component: CreatePermission,
        name: "permissions-create",
        meta: { requiresAuth: true },
    },
    {
        path: "/permissions/:id/edit",
        component: EditPermission,
        name: "permissions-edit",
        meta: { requiresAuth: true },
    },
    {
        path: "/roles",
        component: Roles,
        name: "roles",
        meta: { requiresAuth: true },
    },
    {
        path: "/roles/create",
        component: CreateRole,
        name: "roles-create",
        meta: { requiresAuth: true },
    },
    {
        path: "/roles/:id/edit",
        component: EditRole,
        name: "roles-edit",
        meta: { requiresAuth: true },
    },
    {
        path: "/users",
        component: Users,
        name: "users",
        meta: { requiresAuth: true },
    },
    {
        path: "/users/create",
        component: CreateUser,
        name: "users-create",
        meta: { requiresAuth: true },
    },
    {
        path: "/users/:id/edit",
        component: EditUser,
        name: "users-edit",
        meta: { requiresAuth: true },
    },
];
