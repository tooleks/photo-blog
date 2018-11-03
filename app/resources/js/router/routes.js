import {routeName} from "./identifiers";

import SignIn from "../components/auth/SignIn";
import SignOut from "../components/auth/SignOut";
import NotFound from "../components/layout/NotFound";
import ContactMe from "../components/other/ContactMe";
import PhotoForm from "../components/photos/PhotoForm";
import PhotoGallery from "../components/photos/PhotoGallery";
import PhotoGalleryViewer from "../components/photos/PhotoGalleryViewer";
import PhotoMap from "../components/photos/PhotoMap";
import Subscription from "../components/subscriptions/Subscription";
import Unsubscription from "../components/subscriptions/Unsubscription";
import SubscriptionsTable from "../components/subscriptions/SubscriptionsTable";

const routes = [
    {
        path: "/",
        name: routeName.home,
        redirect: "/photos",
    },
    // Auth
    {
        path: "/sign-in",
        name: routeName.signIn,
        component: SignIn,
    },
    {
        path: "/sign-out",
        name: routeName.signOut,
        component: SignOut,
        meta: {
            requiresAuth: true,
        },
    },
    // Photos
    {
        path: "/photo/add",
        name: routeName.photoAdd,
        component: PhotoForm,
        meta: {
            requiresAuth: true,
        },
    },
    {
        path: "/photo/:id/edit",
        name: routeName.photoEdit,
        component: PhotoForm,
        meta: {
            requiresAuth: true,
        },
    },
    {
        path: "/photo/:id",
        name: routeName.photo,
        component: PhotoGalleryViewer,
        meta: {
            transition: false,
        },
    },
    {
        path: "/photos/map",
        name: routeName.photosMap,
        component: PhotoMap,
    },
    {
        path: "/photos/search/:searchPhrase/:page?",
        name: routeName.photosSearch,
        component: PhotoGallery,
    },
    {
        path: "/photos/tag/:tag/:page?",
        name: routeName.photosTag,
        component: PhotoGallery,
    },
    {
        path: "/photos/:page?",
        name: routeName.photos,
        component: PhotoGallery,
    },
    // Other
    {
        path: "/contact-me",
        name: routeName.contactMe,
        component: ContactMe,
    },
    {
        path: "/subscriptions/:page?",
        name: routeName.subscriptions,
        component: SubscriptionsTable,
        meta: {
            requiresAuth: true,
        },
    },
    {
        path: "/subscription",
        name: routeName.subscription,
        component: Subscription,
    },
    {
        path: "/unsubscription/:token",
        name: routeName.unsubscription,
        component: Unsubscription,
    },
    {
        path: "*",
        name: routeName.notFound,
        component: NotFound,
    },
    {
        path: "/404",
        name: routeName.route404,
        component: NotFound,
    },
];

export default routes;
