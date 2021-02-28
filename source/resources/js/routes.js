import Home from './components/Home'
import Titles from './components/Titles'
import NotFound from './components/NotFound'

export default {
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'home',
            component: Home
        },
        {
            path: '/home',
            name: 'home',
            component: Home
        },
        {
            path: '/titles',
            name: 'titles',
            component: Titles
        },
        {
            path: "*",
            component: NotFound
        },
    ],
}
