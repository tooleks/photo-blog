export default class Subscription {
    /**
     * Subscription constructor.
     *
     * @param {Object} attributes
     * @param {string} attributes.email
     * @param {string} attributes.token
     */
    constructor({email, token}) {
        this.email = email;
        this.token = token;
    }
}
