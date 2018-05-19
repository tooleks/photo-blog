import Vue from "vue";

Vue.directive("focus", {
    inserted: function (el) {
        el.focus();
    },
});

Vue.directive("on-drop-file", {
    inserted: function (el, bindings) {
        const handler = bindings.value;

        const preventDefaultEvents = (event) => {
            event.preventDefault();
            event.stopPropagation();
        };

        const handleOnDrop = (event) => {
            const file = event.dataTransfer.files.item(0);
            handler.call(handler, file);
        };

        el.addEventListener("drag", preventDefaultEvents);
        el.addEventListener("dragstart", preventDefaultEvents);
        el.addEventListener("dragend", preventDefaultEvents);
        el.addEventListener("dragover", preventDefaultEvents);
        el.addEventListener("dragenter", preventDefaultEvents);
        el.addEventListener("dragleave", preventDefaultEvents);
        el.addEventListener("drop", preventDefaultEvents);
        el.addEventListener("drop", handleOnDrop);

        el.$destroyOnDropFileDirective = () => {
            el.removeEventListener("drag", preventDefaultEvents);
            el.removeEventListener("dragstart", preventDefaultEvents);
            el.removeEventListener("dragend", preventDefaultEvents);
            el.removeEventListener("dragover", preventDefaultEvents);
            el.removeEventListener("dragenter", preventDefaultEvents);
            el.removeEventListener("dragleave", preventDefaultEvents);
            el.removeEventListener("drop", preventDefaultEvents);
            el.removeEventListener("drop", handleOnDrop);
        };
    },
    unbind: function (el) {
        el.$destroyOnDropFileDirective();
    },
});
