const listeners = new Map;

export default {
    bind(element, bindings) {
        const onDragAndDrop = (event) => event.preventDefault();
        const onDrop = (event) => bindings.value(event.dataTransfer.files.item(0));
        element.addEventListener("drag", onDragAndDrop);
        element.addEventListener("dragstart", onDragAndDrop);
        element.addEventListener("dragend", onDragAndDrop);
        element.addEventListener("dragover", onDragAndDrop);
        element.addEventListener("dragenter", onDragAndDrop);
        element.addEventListener("dragleave", onDragAndDrop);
        element.addEventListener("drop", onDragAndDrop);
        element.addEventListener("drop", onDrop);
        listeners.set(element, () => {
            element.removeEventListener("drag", onDragAndDrop);
            element.removeEventListener("dragstart", onDragAndDrop);
            element.removeEventListener("dragend", onDragAndDrop);
            element.removeEventListener("dragover", onDragAndDrop);
            element.removeEventListener("dragenter", onDragAndDrop);
            element.removeEventListener("dragleave", onDragAndDrop);
            element.removeEventListener("drop", onDragAndDrop);
            element.removeEventListener("drop", onDrop);
        });
    },
    unbind(element) {
        listeners.get(element)();
        listeners.delete(element);
    },
};
