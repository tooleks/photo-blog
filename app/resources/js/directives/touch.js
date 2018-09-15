import Vue from "vue";
import Hammer from "hammerjs";

Vue.directive("on-swipe-left", {
    inserted: function (el, bindings) {
        const handler = bindings.value;
        Hammer(el).on("swipeleft", () => handler.call(handler));
    },
    unbind: function (el) {
        Hammer(el).off("swipeleft");
    },
});

Vue.directive("on-swipe-right", {
    inserted: function (el, bindings) {
        const handler = bindings.value;
        Hammer(el).on("swiperight", () => handler.call(handler));
    },
    unbind: function (el) {
        Hammer(el).off("swiperight");
    },
});
