import Register from "../pages/register/Register";
import Login from "../pages/login/Login";
import ForgotPassword from "../pages/forgotPassword/ForgotPassword";
import Profile from "../pages/profile/Profile";
import Logout from "../pages/logout/Logout";
import Transactions from "../pages/transactions/Transactions";
import TransactionInfo from "../pages/transactionInfo/TransactionInfo";
import TransactionUpdate from "../pages/transactionUpdate/TransactionUpdate";
import TransactionCreate from "../pages/transactionCreate/TransactionCreate";
import ManageCategories from "../pages/manageCategories/ManageCategories";

export const publicRoutes = [
    {path: "/register", element: <Register/>},
    {path: "/login", element: <Login/>},
    {path: "/login/reset", element: <ForgotPassword/>},
];

export const privateRoutes = [
    {path: "/logout", element: <Logout/>},
    {path: "/profile", element: <Profile/>},
    {path: "/transactions", element: <Transactions/>},
    {path: "/transactions/:id", element: <TransactionInfo/>},
    {path: "/transactions/:id/update", element: <TransactionUpdate/>},
    {path: "/transactions/create", element: <TransactionCreate/>},
    {path: "/categories", element: <ManageCategories/>},
];