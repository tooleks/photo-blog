import Vue from "vue";

Vue.directive("on-key-up", {
    inserted: function (el, bindings) {
        const handler = bindings.value;
        const onKeyUp = (event) => handler.call(handler, event);
        window.addEventListener("keyup", onKeyUp);
        el.$destroyOnKeyUpDirective = () => window.removeEventListener("keyup", onKeyUp);
    },
    unbind: function (el) {
        el.$destroyOnKeyUpDirective();
    },
});
