import Hammer from "hammerjs";

const subscribers = new Map;

const OnSwipeRight = {
    bind(element, bindings) {
        const onSwipeLeft = () => bindings.value();
        const mc = new Hammer(element);
        mc.on("swiperight", onSwipeLeft);
        subscribers.set(element, () => mc.off("swiperight", onSwipeLeft));
    },
    unbind(element) {
        subscribers.get(element)();
        subscribers.delete(element);
    },
};

export default OnSwipeRight;
