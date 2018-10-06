import Hammer from "hammerjs";

export const onSwipeLeft = {
    inserted(el, bindings) {
        const handler = bindings.value;
        Hammer(el).on("swipeleft", () => handler.call(handler));
    },
    unbind(el) {
        Hammer(el).off("swipeleft");
    },
};

export const onSwipeRight = {
    inserted(el, bindings) {
        const handler = bindings.value;
        Hammer(el).on("swiperight", () => handler.call(handler));
    },
    unbind(el) {
        Hammer(el).off("swiperight");
    },
};
