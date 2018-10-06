export const onKeyUp = {
    inserted(el, bindings) {
        const handler = bindings.value;
        const onKeyUp = (event) => handler.call(handler, event);
        window.addEventListener("keyup", onKeyUp);
        el.$destroyOnKeyUpDirective = () => window.removeEventListener("keyup", onKeyUp);
    },
    unbind(el) {
        el.$destroyOnKeyUpDirective();
    },
};
