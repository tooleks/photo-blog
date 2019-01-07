import Hammer from "hammerjs";

const listeners = new Map;

export default {
    bind(element, bindings) {
        const onSwipeLeft = () => bindings.value();
        const eventManager = new Hammer(element);
        eventManager.on("swipeleft", onSwipeLeft);
        listeners.set(element, () => eventManager.off("swipeleft", onSwipeLeft));
    },
    unbind(element) {
        listeners.get(element)();
        listeners.delete(element);
    },
};
