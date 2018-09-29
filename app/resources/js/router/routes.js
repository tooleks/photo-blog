import {
    HOME,
    NOT_FOUND,
    ROUTE_404,
    SIGN_IN,
    SIGN_OUT,
    PHOTO_ADD,
    PHOTO_EDIT,
    PHOTO,
    PHOTOS_MAP,
    PHOTOS_SEARCH,
    PHOTOS_TAG,
    PHOTOS,
    CONTACT_ME,
    SUBSCRIPTIONS,
    SUBSCRIPTION,
    UNSUBSCRIPTION,
    _PAGINATION,
} from "./names";

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
        name: HOME,
        redirect: "/photos",
    },
    // Auth
    {
        path: "/sign-in",
        name: SIGN_IN,
        component: SignIn,
    },
    {
        path: "/sign-out",
        name: SIGN_OUT,
        component: SignOut,
        meta: {
            requiresAuth: true,
        },
    },
    // Photos
    {
        path: "/photo/add",
        name: PHOTO_ADD,
        component: PhotoForm,
        meta: {
            requiresAuth: true,
        },
    },
    {
        path: "/photo/:id/edit",
        name: PHOTO_EDIT,
        component: PhotoForm,
        meta: {
            requiresAuth: true,
        },
    },
    {
        path: "/photo/:id",
        name: PHOTO,
        component: PhotoGalleryViewer,
        meta: {
            transition: false,
        },
    },
    {
        path: "/photos/map",
        name: PHOTOS_MAP,
        component: PhotoMap,
    },
    {
        path: "/photos/search/:searchPhrase",
        name: PHOTOS_SEARCH,
        component: PhotoGallery,
    },
    {
        path: "/photos/search/:searchPhrase/:page",
        name: PHOTOS_SEARCH + _PAGINATION,
        component: PhotoGallery,
    },
    {
        path: "/photos/tag/:tag",
        name: PHOTOS_TAG,
        component: PhotoGallery,
    },
    {
        path: "/photos/tag/:tag/:page",
        name: PHOTOS_TAG + _PAGINATION,
        component: PhotoGallery,
    },
    {
        path: "/photos",
        name: PHOTOS,
        component: PhotoGallery,
    },
    {
        path: "/photos/:page",
        name: PHOTOS + _PAGINATION,
        component: PhotoGallery,
    },
    // Other
    {
        path: "/contact-me",
        name: CONTACT_ME,
        component: ContactMe,
    },
    {
        path: "/subscriptions",
        name: SUBSCRIPTIONS,
        component: SubscriptionsTable,
        meta: {
            requiresAuth: true,
        },
    },
    {
        path: "/subscriptions/:page",
        name: SUBSCRIPTIONS + _PAGINATION,
        component: SubscriptionsTable,
        meta: {
            requiresAuth: true,
        },
    },
    {
        path: "/subscription",
        name: SUBSCRIPTION,
        component: Subscription,
    },
    {
        path: "/unsubscription/:token",
        name: UNSUBSCRIPTION,
        component: Unsubscription,
    },
    {
        path: "*",
        name: NOT_FOUND,
        component: NotFound,
    },
    {
        path: "/404",
        name: ROUTE_404,
        component: NotFound,
    },
];

export default routes;
