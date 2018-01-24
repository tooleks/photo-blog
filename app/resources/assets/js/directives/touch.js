import Vue from "vue";
import Hammer from "hammerjs";

Vue.directive("swipe-left", {
    inserted: function (el, bindings) {
        const handler = bindings.value;
        const hammer = new Hammer(el);
        hammer.on("swipeleft", function () {
            handler.call(handler);
        });
    },
});

Vue.directive("swipe-right", {
    inserted: function (el, bindings) {
        const handler = bindings.value;
        const hammer = new Hammer(el);
        hammer.on("swiperight", function () {
            handler.call(handler);
        });
    },
});
