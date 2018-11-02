import {optional} from "tooleks";
import router from "../../router";

/** @type {Readonly} */
export const HTTP_STATUS = Object.freeze({
    NO_CONTENT: 204,
    UNAUTHORIZED: 401,
    NOT_FOUND: 404,
    UNPROCESSABLE_ENTITY: 422,
});

export default class ApiHandler {
    /**
     * ApiHandler constructor.
     *
     * @param {AlertService} alert
     */
    constructor(alert) {
        this._alert = alert;
        this.onData = this.onData.bind(this);
        this.onError = this.onError.bind(this);
        this._onConnectionError = this._onConnectionError.bind(this);
        this._onUnauthenticatedError = this._onUnauthenticatedError.bind(this);
        this._onNotFoundError = this._onNotFoundError.bind(this);
        this._onValidationError = this._onValidationError.bind(this);
        this._onHttpError = this._onHttpError.bind(this);
    }

    /**
     * @param {*} response
     * @return {Promise<*>}
     */
    async onData(response) {
        if (response.status === HTTP_STATUS.NO_CONTENT || typeof response.data === "object") {
            return response;
        } else {
            throw new SyntaxError;
        }
    }

    /**
     * @param {Error} error
     * @param {Object} options
     * @return {*}
     */
    onError(error, options = {}) {
        switch (optional(() => error.response.status)) {
            case 0: {
                return this._onConnectionError(error, options);
            }
            case HTTP_STATUS.UNAUTHORIZED: {
                return this._onUnauthenticatedError(error, options);
            }
            case HTTP_STATUS.NOT_FOUND: {
                return this._onNotFoundError(error, options);
            }
            case HTTP_STATUS.UNPROCESSABLE_ENTITY: {
                return this._onValidationError(error, options);
            }
            default: {
                return this._onHttpError(error, options);
            }
        }
    }

    /**
     * @param {Error} error
     * @param {Object} options
     * @return {Promise}
     * @private
     */
    async _onConnectionError(error, options) {
        this._alert.error("Remote server connection error. Please, check your internet connection.");
        throw error;
    }

    /**
     * @param {Error} error
     * @param {Object} options
     * @return {Promise}
     * @private
     */
    async _onUnauthenticatedError(error, options) {
        router.push({name: "sign-out"});
        return this._onHttpError(error);
    }

    /**
     * @param {Error} error
     * @param {Object} [options]
     * @param {boolean} [options.suppressNotFoundErrors=false]
     * @return {Promise}
     * @private
     */
    async _onNotFoundError(error, {suppressNotFoundErrors = false} = {}) {
        if (!suppressNotFoundErrors) {
            this._alert.error(error.response.data.message);
        }
        throw error;
    }

    /**
     * @param {Error} error
     * @param {Object} options
     * @return {Promise}
     * @private
     */
    async _onValidationError(error, {}) {
        const errors = optional(() => error.response.data.errors) || {};
        Object.keys(errors).forEach((attribute) => {
            errors[attribute].forEach((message) => this._alert.warning(message));
        });
        throw error;
    }

    /**
     * @param {Error} error
     * @param {Object} options
     * @return {Promise}
     * @private
     */
    async _onHttpError(error, options = {}) {
        if (error instanceof SyntaxError) {
            const title = "Invalid response type.";
            this._alert.error(title);
        } else if (optional(() => error.response)) {
            const title = optional(() => error.response.data.message) || "Internal Server Error.";
            const status = optional(() => error.response.status) || "500";
            this._alert.error(title, `HTTP ${status} Error.`);
        } else {
            const title = optional(() => error.message) || "Unknown Error.";
            this._alert.error(title);
        }
        throw error;
    }
}
