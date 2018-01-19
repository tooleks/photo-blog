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
        component: require("../components/auth/sign-in.vue"),
    },
    {
        path: "/sign-out",
        name: "sign-out",
        component: require("../components/auth/sign-out.vue"),
        meta: {requiresAuth: true},
    },
    // Photos
    {
        path: "/photo/add",
        name: "photo/add",
        component: require("../components/photos/photo-modify.vue"),
        meta: {requiresAuth: true},
    },
    {
        path: "/photo/:id/edit",
        name: "photo/edit",
        component: require("../components/photos/photo-modify.vue"),
        meta: {requiresAuth: true},
    },
    {
        path: "/photo/:id",
        name: "photo",
        component: require("../components/photos/photo-gallery-viewer.vue"),
    },
    {
        path: "/photos",
        name: "photos",
        component: require("../components/photos/photo-gallery.vue"),
    },
    {
        path: "/photos/:page",
        name: "photos-with-page",
        component: require("../components/photos/photo-gallery.vue"),
    },
    {
        path: "/photos/search/:search_phrase",
        name: "photos-search",
        component: require("../components/photos/photo-gallery.vue"),
    },
    {
        path: "/photos/search/:search_phrase/:page",
        name: "photos-search-with-page",
        component: require("../components/photos/photo-gallery.vue")
    },
    {
        path: "/photos/tag/:tag",
        name: "photos-tag",
        component: require("../components/photos/photo-gallery.vue"),
    },
    {
        path: "/photos/tag/:tag/:page",
        name: "photos-tag-with-page",
        component: require("../components/photos/photo-gallery.vue")
    },
    // Other
    {
        path: "/contact-me",
        name: "contact-me",
        component: require("../components/other/contact-me.vue"),
    },
    {
        path: "/subscription",
        name: "subscription",
        component: require("../components/other/subscription.vue"),
    },
    {
        path: "/unsubscription/:token",
        name: "unsubscription",
        component: require("../components/other/unsubscription.vue"),
    },
    {
        path: "*",
        name: "not-found",
        component: require("../components/layout/not-found.vue"),
    },
    {
        path: "/404",
        name: "404",
        component: require("../components/layout/not-found.vue"),
    },
];

export default routes;
