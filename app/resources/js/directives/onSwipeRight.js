import Hammer from "hammerjs";

const onSwipeRight = {
    inserted(element, bindings) {
        Hammer(element).on("swiperight", () => bindings.value());
    },
    unbind(element) {
        Hammer(element).off("swiperight");
    },
};

export default onSwipeRight;
