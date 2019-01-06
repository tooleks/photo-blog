const onDropFile = {
    inserted(element, bindings) {
        // Event handler to prevent the default action of the event being triggered.
        const preventDefault = (event) => {
            event.preventDefault();
        };

        // Event handler to call the directive's handler function with the dropped file as an argument.
        const onDrop = (event) => {
            event.preventDefault();
            const file = event.dataTransfer.files.item(0);
            bindings.value(file);
        };

        element.addEventListener("drag", preventDefault);
        element.addEventListener("dragstart", preventDefault);
        element.addEventListener("dragend", preventDefault);
        element.addEventListener("dragover", preventDefault);
        element.addEventListener("dragenter", preventDefault);
        element.addEventListener("dragleave", preventDefault);
        element.addEventListener("drop", onDrop);

        element.$destroyOnDropFile = () => {
            element.removeEventListener("drag", preventDefault);
            element.removeEventListener("dragstart", preventDefault);
            element.removeEventListener("dragend", preventDefault);
            element.removeEventListener("dragover", preventDefault);
            element.removeEventListener("dragenter", preventDefault);
            element.removeEventListener("dragleave", preventDefault);
            element.removeEventListener("drop", onDrop);
        };
    },
    unbind(element) {
        element.$destroyOnDropFile();
    },
};

export default onDropFile;
