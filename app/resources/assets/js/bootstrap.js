window._ = require("lodash");

try {
    window.$ = window.jQuery = require("jquery");
    window.Popper = require("popper.js").default;
    require("bootstrap");
} catch (e) {
}

window.moment = require("moment");
window.axios = require("axios");
window.axios.defaults.headers.common["Accept"] = "application/json";
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

const token = document.head.querySelector("meta[name=\"csrf-token\"]");
if (token) {
    window.axios.defaults.headers.common["X-CSRF-TOKEN"] = token.content;
} else {
    console.error("CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token");
}
