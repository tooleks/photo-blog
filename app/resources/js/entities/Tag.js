export default class Tag {
    /**
     * Tag constructor.
     *
     * @param {Object} attributes
     * @param {string} attributes.value
     */
    constructor({value}) {
        this.value = value;
        this.valueOf = this.valueOf.bind(this);
        this.toString = this.toString.bind(this);
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
        return String(this.valueOf());
    }
}

/**
 * @param {string} value
 * @return {Tag}
 */
Tag.fromValue = function fromValue(value) {
    return new Tag({value});
};
