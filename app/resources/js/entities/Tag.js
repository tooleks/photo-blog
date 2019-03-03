export default class Tag {
    /**
     * @param {Object} attributes
     * @param {string} attributes.value
     */
    constructor({value}) {
        this.value = value;
        this.valueOf = this.valueOf.bind(this);
        this.toString = this.toString.bind(this);
        this.clone = this.clone.bind(this);
    }

    /**
     * @returns {string}
     */
    valueOf() {
        return this.value;
    }

    /**
     * @returns {string}
     */
    toString() {
        return String(this.valueOf());
    }

    /**
     * @returns {Tag}
     */
    clone() {
        return new Tag({
            value: this.value,
        });
    }
}

/**
 * @param {string} value
 * @returns {Tag}
 */
Tag.fromValue = function fromValue(value) {
    return new Tag({value});
};
