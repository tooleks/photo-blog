import Vue from "vue";

Vue.directive("on-mouse-move", {
    inserted: function (el, bindings) {
        const handler = bindings.value;
        const onMouseMove = (event) => handler.call(handler, event);
        window.addEventListener("mousemove", onMouseMove);
        el.$destroyOnMouseMoveDirective = () => window.removeEventListener("mousemove", onMouseMove);
    },
    unbind: function (el) {
        el.$destroyOnMouseMoveDirective();
    },
});
