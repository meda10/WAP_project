import Home from './components/Home';
import Title from './components/Title';
import NotFound from './components/NotFound';
import Login from './components/Login';
import Register from './components/Register';
import ForgotPassword from './components/ForgotPassword';
import Settings from './components/Settings';
import Administration from './components/Administration';
import Series from './components/Series';
import Movies from './components/Movies';
import Checkout from './components/Checkout';
import Cart from './components/Cart';


export default {
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'home',
            component: Home
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
            path: '/film/',
            name: 'movie',
            component: Title
        },
        {
            path: '/film/:titleName',
            name: 'movie',
            component: Title
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
            path: '/serial',
            name: 'serial',
            component: Title
        },
        {
            path: '/serial/:titleName',
            name: 'serial',
            component: Title
        },
        {
            path: '/pokladna',
            name: 'checkout',
            component: Checkout,
        },
        {
            path: '/kosik',
            name: 'cart',
            component: Cart
        },
        {
            path: "*",
            name: 'notfound',
            component: NotFound
        },
    ],
}
