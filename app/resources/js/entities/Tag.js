export default class Tag {
    /**
     * Tag constructor.
     *
     * @param {Object} attributes
     * @param {string} attributes.value
     */
    constructor({value}) {
        this.value = value;
    }

    /**
     * @return {string}
     */
    valueOf() {
        return String(this.value);
    }

    /**
     * @return {string}
     */
    toString() {
        return this.valueOf();
    }
}

/**
 * @param {string} value
 * @return {Tag}
 */
Tag.fromValue = function fromValue(value) {
    return new Tag({value});
};
