import * as route from "./names";

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
        name: route.home,
        redirect: "/photos",
    },
    // Auth
    {
        path: "/sign-in",
        name: route.signIn,
        component: SignIn,
    },
    {
        path: "/sign-out",
        name: route.signOut,
        component: SignOut,
        meta: {
            requiresAuth: true,
        },
    },
    // Photos
    {
        path: "/photo/add",
        name: route.photoAdd,
        component: PhotoForm,
        meta: {
            requiresAuth: true,
        },
    },
    {
        path: "/photo/:id/edit",
        name: route.photoEdit,
        component: PhotoForm,
        meta: {
            requiresAuth: true,
        },
    },
    {
        path: "/photo/:id",
        name: route.photo,
        component: PhotoGalleryViewer,
        meta: {
            transition: false,
        },
    },
    {
        path: "/photos/map",
        name: route.photosMap,
        component: PhotoMap,
    },
    {
        path: "/photos/search/:searchPhrase/:page?",
        name: route.photosSearch,
        component: PhotoGallery,
    },
    {
        path: "/photos/tag/:tag/:page?",
        name: route.photosTag,
        component: PhotoGallery,
    },
    {
        path: "/photos/:page?",
        name: route.photos,
        component: PhotoGallery,
    },
    // Other
    {
        path: "/contact-me",
        name: route.contactMe,
        component: ContactMe,
    },
    {
        path: "/subscriptions/:page?",
        name: route.subscriptions,
        component: SubscriptionsTable,
        meta: {
            requiresAuth: true,
        },
    },
    {
        path: "/subscription",
        name: route.subscription,
        component: Subscription,
    },
    {
        path: "/unsubscription/:token",
        name: route.unsubscription,
        component: Unsubscription,
    },
    {
        path: "*",
        name: route.notFound,
        component: NotFound,
    },
    {
        path: "/404",
        name: route.route404,
        component: NotFound,
    },
];

export default routes;
