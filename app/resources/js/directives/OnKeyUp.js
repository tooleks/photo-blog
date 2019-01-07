const listeners = new Map;

export default {
    bind(element, bindings) {
        const onKeyUp = (event) => bindings.value(event);
        window.addEventListener("keyup", onKeyUp);
        listeners.set(element, () => window.removeEventListener("keyup", onKeyUp));
    },
    unbind(element) {
        listeners.get(element)();
        listeners.delete(element);
    },
};
