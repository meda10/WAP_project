import Home from './components/Home';
import Titles from './components/Titles';
import NotFound from './components/NotFound';
import Login from './components/Login';
import Register from './components/Register';
import ForgotPassword from './components/ForgotPassword';
import Settings from './components/Settings';
import Administration from './components/Administration';
import Series from './components/Series';
import Movies from './components/Movies';
import Checkout from './components/Checkout';
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
            path: '/tituly',
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
            path: '/registrovat',
            name: 'register',
            component: Register
        },
        {
            path: '/prihlasit',
            name: 'login',
            component: Login
        },
        {
            path: '/heslo',
            name: 'password',
            component: ForgotPassword
        },
        {
            path: '/admin',
            name: 'administration',
            component: Administration
        },
        {
            path: '/nastaveni',
            name: 'settings',
            component: Settings
        },
        {
            path: '/filmy',
            name: 'movies',
            component: Movies
        },
        {
            path: '/filmy/:movieGenre',
            name: 'movies',
            component: Movies
        },
        {
            path: '/serialy',
            name: 'series',
            component: Series
        },
        {
            path: '/serialy/:serialGenre',
            name: 'series',
            component: Series
        },
        {
            path: '/platba',
            name: 'checkout',
            component: Checkout
        },
        {
            path: "*",
            component: NotFound
        },
    ],
}
