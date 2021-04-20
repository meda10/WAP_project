import VueRouter from 'vue-router';
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
import UserReservations from "./components/UserReservations";
import Users from "./components/Users";
import Actors from "./components/Actors";
import Stores from "./components/Stores";
import StoreAdd from "./components/StoreAdd";
import StoreEdit from "./components/StoreEdit";
import Discounts from "./components/Discounts";
import DiscountAdd from "./components/DiscountAdd";


const router = new VueRouter({
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
            component: Administration,
            meta: {
                authRequired: 'true',
                role: ['employee', 'director', 'manager'],
            },
        },
        {
            path: '/admin/pridat_titul',
            name: 'titleadd',
            component: TitleAdd,
            meta: {
                authRequired: 'true',
                role: ['director', 'manager'],
            },
        },
        {
            path: '/film/:titleName/upravit',
            name: 'titleEdit',
            component: TitleEdit,
            meta: {
                authRequired: 'true',
                role: ['director', 'manager'],
            },
        },
        {
            path: '/admin/slevy',
            name: 'discounts',
            component: Discounts,
            meta: {
                authRequired: 'true',
                role: ['director'],
            },
        },
        {
            path: '/admin/slevy/pridat_slevu',
            name: 'discountAdd',
            component: DiscountAdd,
            meta: {
                authRequired: 'true',
                role: ['director'],
            },
        },
        {
            path: '/admin/obchody',
            name: 'stores',
            component: Stores,
            meta: {
                authRequired: 'true',
                role: ['employee', 'director', 'manager'],
            },
        },
        {
            path: '/admin/obchody/pridat_obchod',
            name: 'storeAdd',
            component: StoreAdd,
            meta: {
                authRequired: 'true',
                role: ['director'],
            },
        },
        {
            path: '/admin/obchody/upravit_obchod/:id',
            name: 'storeEdit',
            component: StoreEdit,
            meta: {
                authRequired: 'true',
                role: ['director'],
            },
        },
        {
            path: '/admin/herci',
            name: 'actors',
            component: Actors,
            meta: {
                authRequired: 'true',
                role: ['employee', 'director', 'manager'],
            },
        },
        {
            path: '/admin/herci/pridat_herce',
            name: 'actorAdd',
            component: ActorAdd,
            meta: {
                authRequired: 'true',
                role: ['director'],
            },
        },
        {
            path: '/admin/herci/upravit_herce/:id',
            name: 'actorEdit',
            component: ActorEdit,
            meta: {
                authRequired: 'true',
                role: ['director'],
            },
        },
        {
            path: '/admin/uzivatele',
            name: 'users',
            component: Users,
            meta: {
                authRequired: 'true',
                role: ['employee', 'director', 'manager'],
            },
        },
        {
            path: '/admin/uzivatele/pridat_uzivatele',
            name: 'userAdd',
            component: UserAdd,
            meta: {
                authRequired: 'true',
                role: ['employee', 'director', 'manager'],
            },
        },
        {
            path: '/admin/uzivatele/rezervace/:id',
            name: 'userReservationsId',
            component: UserReservations,
            meta: {
                authRequired: 'true',
                role: ['employee', 'director', 'manager'],
            },
        },
        {
            path: '/admin/uzivatele/rezervace',
            name: 'userReservations',
            component: UserReservations,
            meta: {
                authRequired: 'true',
                role: ['employee', 'director', 'manager'],
            },
        },
        {
            path: '/admin/uzivatele/upravit_uzivatele/:id',
            name: 'userEdit',
            component: UserEdit,
            meta: {
                authRequired: 'true',
                role: ['director', 'manager'],
            },
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
});

export default router;

router.afterEach((to, from) => {
    if (to.name === 'home' && from.name === 'login') {
        router.go();
    }
});

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.authRequired)) {
        console.log(window.Laravel);
        if(window.Laravel.jsPermissions === ''){
            router.push({name: 'login'});
        }else {
            next();
        }
    } else {
        next();
    }
    if (to.matched.some(record => record.meta.role)) {
        if(window.Laravel.jsPermissions !== ''){
            console.log(window.Laravel);
            let roles = to.meta.role;
            if (!roles.includes(window.Laravel.jsPermissions['roles'][0])) {
                router.push({name: 'home'});
            } else {
                next();
            }
        }else {
            next();
        }
    } else {
        next();
    }
    next();
});
