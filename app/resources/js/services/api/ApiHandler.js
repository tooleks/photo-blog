import router from "../../router";
import {routeName} from "../../router/identifiers";

/** @type {Readonly} */
export const HTTP_STATUS = Object.freeze({
    NO_CONTENT: 204,
    UNAUTHORIZED: 401,
    NOT_FOUND: 404,
    UNPROCESSABLE_ENTITY: 422,
});

export default class ApiHandler {
    /**
     * @param {AlertService} alert
     */
    constructor(alert) {
        this._alert = alert;
        this.onData = this.onData.bind(this);
        this.onError = this.onError.bind(this);
        this._onResponseError = this._onResponseError.bind(this);
        this._onSyntaxError = this._onSyntaxError.bind(this);
        this._onConnectionError = this._onConnectionError.bind(this);
        this._onUnauthenticatedResponseError = this._onUnauthenticatedResponseError.bind(this);
        this._onNotFoundResponseError = this._onNotFoundResponseError.bind(this);
        this._onValidationResponseError = this._onValidationResponseError.bind(this);
        this._onOtherResponseError = this._onOtherResponseError.bind(this);
    }

    /**
     * @param {*} response
     * @returns {*}
     */
    onData(response) {
        // Empty response data.
        if (response.status === HTTP_STATUS.NO_CONTENT) {
            return response;
        }
        // `application/json` response data.
        else if (typeof response.data === "object") {
            return response;
        }
        // Invalid response data.
        else {
            throw new SyntaxError;
        }
    }

    /**
     * @param {Error} error
     * @returns {*}
     */
    onError(error) {
        if (error.response) {
            return this._onResponseError(error);
        } else if (error instanceof SyntaxError) {
            return this._onSyntaxError(error);
        }

        throw error;
    }

    /**
     * @param {Error} error
     * @returns {*}
     * @private
     */
    _onResponseError(error) {
        switch (error.response.status) {
            case 0: {
                return this._onConnectionError(error);
            }
            case HTTP_STATUS.UNAUTHORIZED: {
                return this._onUnauthenticatedResponseError(error);
            }
            case HTTP_STATUS.NOT_FOUND: {
                return this._onNotFoundResponseError(error);
            }
            case HTTP_STATUS.UNPROCESSABLE_ENTITY: {
                return this._onValidationResponseError(error);
            }
            default: {
                return this._onOtherResponseError(error);
            }
        }
    }

    /**
     * @private
     * @returns {*}
     * @private
     */
    _onSyntaxError(error) {
        const title = "Invalid response type.";
        this._alert.error(title);
        throw error;
    }

    /**
     * @param {Error} error
     * @returns {*}
     * @private
     */
    _onConnectionError(error) {
        this._alert.error("Remote server connection error. Please, check your internet connection.");
        throw error;
    }

    /**
     * @param {Error} error
     * @returns {*}
     * @private
     */
    _onUnauthenticatedResponseError(error) {
        router.push({name: routeName.signOut});
        return this._onOtherResponseError(error);
    }

    /**
     * @param {Error} error
     * @returns {*}
     * @private
     */
    _onNotFoundResponseError(error) {
        const url = new URL(error.request.responseURL);
        if (!url.pathname.includes("/posts")) {
            this._alert.error(error.response.data.message);
        }
        throw error;
    }

    /**
     * @param {Error} error
     * @returns {*}
     * @private
     */
    _onValidationResponseError(error) {
        const errors = error.response.data.errors || {};
        Object.keys(errors).forEach((attribute) => {
            errors[attribute].forEach((message) => this._alert.warning(message));
        });
        throw error;
    }

    /**
     * @param {Error} error
     * @returns {*}
     * @private
     */
    _onOtherResponseError(error) {
        const title = error.response.data.message || error.response.statusText;
        const status = error.response.status;
        this._alert.error(title, `HTTP ${status} Error.`);
        throw error;
    }
}
