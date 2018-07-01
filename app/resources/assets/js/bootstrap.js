// Vue.
window.Vue = require("vue");

// Lodash.
window._ = require("lodash");

// jQuery.
window.$ = window.jQuery = require("jquery");

// Bootstrap.
window.Popper = require("popper.js").default;
require("bootstrap");

// Moment.
window.moment = require("moment");

// Axios.
window.axios = require("axios");
window.axios.defaults.headers.common["Accept"] = "application/json";
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
const token = document.head.querySelector("meta[name=\"csrf-token\"]");
if (token) {
    window.axios.defaults.headers.common["X-CSRF-TOKEN"] = token.content;
} else {
    console.error("CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token");
}
