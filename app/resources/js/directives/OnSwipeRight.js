import Hammer from "hammerjs";

const listeners = new Map;

export default {
    bind(element, bindings) {
        const onSwipeLeft = () => bindings.value();
        const eventManager = new Hammer(element);
        eventManager.on("swiperight", onSwipeLeft);
        listeners.set(element, () => eventManager.off("swiperight", onSwipeLeft));
    },
    unbind(element) {
        listeners.get(element)();
        listeners.delete(element);
    },
};
