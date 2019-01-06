const onKeyUp = {
    inserted(element, bindings) {
        // Event handler to call the directive's handler function when an event occurs.
        const onKeyUp = (event) => bindings.value(event);
        window.addEventListener("keyup", onKeyUp);
        element.$destroyOnKeyUp = () => window.removeEventListener("keyup", onKeyUp);
    },
    unbind(element) {
        element.$destroyOnKeyUp();
    },
};

export default onKeyUp;
