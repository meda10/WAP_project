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
import TitleAdd from "./components/TitleAdd";
import ActorAdd from "./components/ActorAdd";
import Reservations from "./components/Reservations";
import TitleEdit from "./components/TitleEdit";
import ActorEdit from "./components/ActorEdit";
import UserEdit from "./components/UserEdit";
import UserAdd from "./components/UserAdd";
import Users from "./components/Users";
import Actors from "./components/Actors";
import Stores from "./components/Stores";
import StoreAdd from "./components/StoreAdd";
import StoreEdit from "./components/StoreEdit";


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
            path: '/rezervace',
            name: 'reservations',
            component: Reservations
        },
        {
            path: '/admin',
            name: 'administration',
            component: Administration
        },
        {
            path: '/admin/pridat_titul',
            name: 'titleadd',
            component: TitleAdd
        },
        {
            path: '/film/:titleName/upravit',
            name: 'titleEdit',
            component: TitleEdit
        },
        {
            path: '/admin/obchody',
            name: 'stores',
            component: Stores
        },
        {
            path: '/admin/obchody/pridat_obchod',
            name: 'storeAdd',
            component: StoreAdd
        },
        {
            path: '/admin/obchody/upravit_obchod/:id',
            name: 'storeEdit',
            component: StoreEdit
        },
        {
            path: '/admin/herci',
            name: 'actors',
            component: Actors
        },
        {
            path: '/admin/herci/pridat_herce',
            name: 'actorAdd',
            component: ActorAdd
        },
        {
            path: '/admin/herci/upravit_herce/:id',
            name: 'actorEdit',
            component: ActorEdit
        },
        {
            path: '/admin/uzivatele',
            name: 'users',
            component: Users
        },
        {
            path: '/admin/uzivatele/pridat_uzivatele',
            name: 'userAdd',
            component: UserAdd
        },
        {
            path: '/admin/uzivatele/upravit_uzivatele/:id',
            name: 'userEdit',
            component: UserEdit
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
