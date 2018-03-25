const routes = [
    {
        path: "/",
        name: "home",
        redirect: "/photos",
    },
    // Auth
    {
        path: "/sign-in",
        name: "sign-in",
        component: require("../components/auth/sign-in"),
    },
    {
        path: "/sign-out",
        name: "sign-out",
        component: require("../components/auth/sign-out"),
        meta: {
            requiresAuth: true,
        },
    },
    // Photos
    {
        path: "/photo/add",
        name: "photo/add",
        component: require("../components/photos/photo-modify"),
        meta: {
            requiresAuth: true,
        },
    },
    {
        path: "/photo/:id/edit",
        name: "photo/edit",
        component: require("../components/photos/photo-modify"),
        meta: {
            requiresAuth: true,
        },
    },
    {
        path: "/photo/:id",
        name: "photo",
        component: require("../components/photos/photo-gallery-viewer"),
        meta: {
            transition: false,
        },
    },
    {
        path: "/photos/map",
        name: "photos-map",
        component: require("../components/photos/photo-map"),
    },
    {
        path: "/photos/search/:search_phrase",
        name: "photos-search",
        component: require("../components/photos/photo-gallery"),
    },
    {
        path: "/photos/search/:search_phrase/:page",
        name: "photos-search-with-page",
        component: require("../components/photos/photo-gallery")
    },
    {
        path: "/photos/tag/:tag",
        name: "photos-tag",
        component: require("../components/photos/photo-gallery"),
    },
    {
        path: "/photos/tag/:tag/:page",
        name: "photos-tag-with-page",
        component: require("../components/photos/photo-gallery")
    },
    {
        path: "/photos",
        name: "photos",
        component: require("../components/photos/photo-gallery"),
    },
    {
        path: "/photos/:page",
        name: "photos-with-page",
        component: require("../components/photos/photo-gallery"),
    },
    // Other
    {
        path: "/contact-me",
        name: "contact-me",
        component: require("../components/other/contact-me"),
    },
    {
        path: "/subscription",
        name: "subscription",
        component: require("../components/other/subscription"),
    },
    {
        path: "/unsubscription/:token",
        name: "unsubscription",
        component: require("../components/other/unsubscription"),
    },
    {
        path: "*",
        name: "not-found",
        component: require("../components/layout/not-found"),
    },
    {
        path: "/404",
        name: "404",
        component: require("../components/layout/not-found"),
    },
];

export default routes;
