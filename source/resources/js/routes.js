import Home from './components/Home';
import Titles from './components/Titles';
import NotFound from './components/NotFound';
import Login from './components/Login';
import Register from './components/Register';
import ForgotPassword from './components/ForgotPassword';
import axios from 'axios';


export default {
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'home',
            component: Home
        },
        {
            path: '/titles',
            name: 'titles',
            component: Titles,
            beforeEnter: (to, from, next) => {
                axios.get('/api/authenticated').then(() => {
                    next();
                }).catch(() => {
                    return next( {name: 'login'} );
                });
            }
        },
        {
            path: '/register',
            name: 'register',
            component: Register
        },
        {
            path: '/login',
            name: 'login',
            component: Login
        },
        {
            path: '/password',
            name: 'password',
            component: ForgotPassword
        },
        {
            path: "*",
            component: NotFound
        },
    ],
}
