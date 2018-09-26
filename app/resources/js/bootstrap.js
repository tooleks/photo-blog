import Vue from "vue";
import moment from "moment";
import axios from "axios";
import _ from "lodash";
import jQuery from "jquery";
import Popper from "popper.js";
import "bootstrap";

window.Vue = Vue;

window.moment = moment;

window._ = _;

window.$ = window.jQuery = jQuery;

window.Popper = Popper;

window.axios = axios;
window.axios.defaults.headers.common["Accept"] = "application/json";
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
const token = document.head.querySelector("meta[name=\"csrf-token\"]");
if (token) {
    window.axios.defaults.headers.common["X-CSRF-TOKEN"] = token.content;
} else {
    console.error("CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token");
}
