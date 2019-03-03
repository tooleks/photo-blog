/**
 * @param {string} tag
 * @param {Object} attributes
 * @returns {Node}
 */
export default function (tag, attributes) {
    // Find the element in the head tag.
    const elements = document.head.querySelectorAll(tag);
    let element = Array.from(elements).find((element) => {
        return Object.keys(attributes).every((key) => attributes[key] === element.getAttribute(key));
    });

    // If the element doesn't exist create a new one.
    if (!element) {
        element = document.createElement(tag);
        Object.keys(attributes).forEach((key) => element.setAttribute(key, attributes[key]));
        document.head.appendChild(element);
    }

    return element;
}
