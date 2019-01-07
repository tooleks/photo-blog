import Hammer from "hammerjs";

const subscribers = new Map;

const OnSwipeLeft = {
    inserted(element, bindings) {
        const onSwipeLeft = () => bindings.value();
        const mc = new Hammer(element);
        mc.on("swipeleft", onSwipeLeft);
        subscribers.set(element, () => mc.off("swipeleft", onSwipeLeft));
    },
    unbind(element) {
        subscribers.get(element)();
        subscribers.delete(element);
    },
};

export default OnSwipeLeft;
