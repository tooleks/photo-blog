import Hammer from "hammerjs";

const onSwipeLeft = {
    inserted(element, bindings) {
        Hammer(element).on("swipeleft", () => bindings.value());
    },
    unbind(element) {
        Hammer(element).off("swipeleft");
    },
};

export default onSwipeLeft;
