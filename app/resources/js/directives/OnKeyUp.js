const subscribers = new Map;

const OnKeyUp = {
    bind(element, bindings) {
        const onKeyUp = (event) => bindings.value(event);
        window.addEventListener("keyup", onKeyUp);
        subscribers.set(element, () => window.removeEventListener("keyup", onKeyUp));
    },
    unbind(element) {
        subscribers.get(element)();
        subscribers.delete(element);
    },
};

export default OnKeyUp;
