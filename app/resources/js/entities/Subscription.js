export default class Subscription {
    /**
     * @param {Object} attributes
     * @param {string} attributes.email
     * @param {string} attributes.token
     */
    constructor({email, token}) {
        this.email = email;
        this.token = token;
        this.clone = this.clone.bind(this);
    }

    /**
     * @returns {Subscription}
     */
    clone() {
        return new Subscription({
            email: this.email,
            token: this.token,
        });
    }
}
