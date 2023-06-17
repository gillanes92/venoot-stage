import Vue from 'vue'
import VueRouter from 'vue-router'
import Actors from './views/Actors'
import Company from './views/Company'
import ControlPanel from './views/ControlPanel'
import Dashboard from './views/Dashboard'
import Databases from './views/Databases'
import Discounts from './views/Discounts'
import Estimate from './views/Estimate'
import Estimator from './views/Estimator'
import Event from './views/Event'
import EventDashboard from './views/EventDashboard'
import WebinarSingleDashboard from './views/WebinarSingleDashboard'
import Events from './views/Events'
import Home from './views/Home'
import Login from './views/Login'
import Permissions from './views/Permissions'
import Polls from './views/Polls'
import SetLanding from './views/SetLanding'
import SignUp from './views/SignUp'
import Sponsors from './views/Sponsors'
import Templates from './views/Templates'
import User from './views/User'
import Users from './views/Users'
import VerifyEmail from './views/VerifyEmail'
import WebinarAdmin from './views/WebinarAdmin'
import WebinarPackages from './views/WebinarPackages'
import WebinarDashboard from './views/WebinarDashboard'
import Webinars from './views/Webinars'
import WebpayResponse from './views/WebpayResponse'
import Welcome from './views/Welcome'
import Tutorials from './views/Tutorials'

Vue.use(VueRouter)

const router = new VueRouter({
    mode: 'history',

    routes: [
        {
            path: '/home',
            name: 'home',
            component: Home,
            meta: {
                auth: true,
                company: true,
            },
        },

        {
            path: '/',
            name: 'welcome',
            component: Welcome,
            meta: {
                auth: undefined,
                company: undefined,
            },
        },
        {
            path: '/login',
            name: 'login',
            component: Login,
            meta: {
                auth: false,
                company: undefined,
            },
        },
        {
            path: '/signup',
            name: 'signup',
            component: SignUp,
            meta: {
                auth: false,
                company: undefined,
            },
        },
        {
            path: '/verify',
            name: 'verify',
            component: VerifyEmail,
            meta: {
                auth: undefined,
                company: undefined,
            },
        },
        {
            path: '/dashboard',
            name: 'dashboard',
            component: Dashboard,
            meta: {
                auth: true,
                company: true,
            },
        },
        {
            path: '/users/:id',
            name: 'user',
            component: User,
            meta: {
                auth: true,
                company: undefined,
            },
        },

        {
            path: '/control-panel/:id',
            name: 'control_panel',
            component: ControlPanel,
            meta: {
                auth: true,
                company: true,
            },
        },

        {
            path: '/users',
            name: 'users',
            component: Users,
            meta: {
                auth: true,
                company: true,
            },
        },

        {
            path: '/permissions',
            name: 'permissions',
            component: Permissions,
            meta: {
                auth: true,
                company: true,
            },
        },

        {
            path: '/companies/:id',
            name: 'companyPatch',
            component: Company,
            meta: {
                auth: true,
                company: true,
            },
        },
        {
            path: '/companies',
            name: 'companyPost',
            component: Company,
            meta: {
                auth: true,
                company: false,
            },
        },
        {
            path: '/actors',
            name: 'actors',
            component: Actors,
            meta: {
                auth: true,
                company: true,
            },
        },
        {
            path: '/databases',
            name: 'databases',
            component: Databases,
            meta: {
                auth: true,
                company: true,
            },
        },
        {
            path: '/events',
            name: 'events',
            component: Events,
            meta: {
                auth: true,
                company: true,
            },
        },

        {
            path: '/webinars',
            name: 'webinars',
            component: Webinars,
            meta: {
                auth: true,
                company: true,
            },
        },

        {
            path: '/templates_index',
            name: 'templates',
            component: Templates,
            meta: {
                auth: true,
                company: true,
            },
        },

        {
            path: '/webinar-admin',
            name: 'webinar-admin',
            component: WebinarAdmin,
            meta: {
                auth: true,
                company: true,
            },
        },

        {
            path: '/webinarpackages',
            name: 'webinarpackages',
            component: WebinarPackages,
            meta: {
                auth: undefined,
                company: undefined,
            },
        },

        {
            path: '/webinardashboard',
            name: 'webinardashboard',
            component: WebinarDashboard,
            meta: {
                auth: true,
                company: true,
            },
        },

        {
            path: '/webinarsingledashboard',
            name: 'webinarsingledashboard',
            component: WebinarSingleDashboard,
            meta: {
                auth: true,
                company: true,
            },
        },

        {
            path: '/tutorials',
            name: 'tutorials',
            component: Tutorials,
            meta: {
                auth: true,
                company: undefined,
            },
        },

        // {
        //     path: '/templates/:id/undefined',
        //     redirect: '/templates',
        // },

        // {
        //     path: '/templates/:id/builder',
        //     name: 'builder',
        //     component: Builder,
        //     meta: {
        //         auth: true,
        //         company: true,
        //     },
        // },

        {
            path: '/event',
            name: 'event',
            component: Event,
            meta: {
                auth: true,
                company: true,
            },
        },

        {
            path: '/event-dashboard/:id',
            name: 'event_dashboard',
            component: EventDashboard,
            meta: {
                auth: true,
                company: true,
            },
        },

        {
            path: '/sponsors',
            name: 'sponsors',
            component: Sponsors,
            meta: {
                auth: true,
                company: true,
            },
        },

        {
            path: '/polls',
            name: 'polls',
            component: Polls,
            meta: {
                auth: true,
                company: true,
            },
        },

        {
            path: '/discounts',
            name: 'discounts',
            component: Discounts,
            meta: {
                auth: true,
                company: true,
            },
        },

        {
            path: '/paid',
            name: 'paid',
            component: WebpayResponse,
            meta: {
                auth: true,
                company: true,
            },
        },

        // Estimate Routes
        {
            path: '/estimator',
            name: 'estimator',
            component: Estimator,
            meta: { auth: true, company: true },
        },

        {
            path: '/event/:id/set-landing',
            name: 'event-set-landing',
            component: SetLanding,
            meta: { auth: true, company: true },
        },

        {
            path: '/estimate/event',
            name: 'estimate',
            component: Estimate,
            props: { category: 'event' },
            meta: { auth: true, company: true },
        },

        {
            path: '/estimate/newsletter',
            name: 'newsletter',
            component: Estimate,
            props: { category: 'newsletter' },
            meta: { auth: true, company: true },
        },

        /**
         * Ignored routes
         */
        {
            path: '/storage',
            meta: {
                auth: true,
                company: undefined,
            },
        },
        {
            path: '*',
            redirect: '/',
            meta: {
                auth: undefined,
                company: undefined,
            },
        },
    ],

    scrollBehavior: (to) => {
        if (to.hash) {
            return new Promise((resolve) => {
                setTimeout(() => {
                    resolve({ selector: to.hash })
                }, 1000)
            })
        }
    },
})

const originalPush = router.push
router.push = function(location, onComplete, onAbort) {
    return originalPush.call(this, location, onComplete, onAbort).catch((e) => {})
}

export default router
