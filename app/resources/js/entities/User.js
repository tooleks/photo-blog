import moment from "moment";

export default class User {
    /**
     * User constructor.
     *
     * @param {Object} attributes
     * @param {number} attributes.id
     * @param {string} attributes.name
     * @param {number} attributes.expiresIn
     */
    constructor({id, name, expiresIn}) {
        this.id = id;
        this.name = name;
        this.expiresIn = expiresIn;
    }

    /**
     * @return {*}
     */
    get expiresAt() {
        return moment.utc().add(this.expiresIn, "seconds");
    }
}
