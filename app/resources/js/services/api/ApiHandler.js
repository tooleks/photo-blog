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
        this._onResponseError = this._onResponseError.bind(this);
        this._onConnectionError = this._onConnectionError.bind(this);
        this._onUnauthenticatedError = this._onUnauthenticatedError.bind(this);
        this._onNotFoundError = this._onNotFoundError.bind(this);
        this._onValidationError = this._onValidationError.bind(this);
        this._onHttpError = this._onHttpError.bind(this);
    }

    /**
     * @param {*} response
     * @return {*}
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
     * @return {*}
     */
    onError(error) {
        if (error.response) {
            return this._onResponseError(error);
        } else if (error instanceof SyntaxError) {
            return this._onSyntaxError(error);
        }

        return error;
    }

    /**
     * @param {Error} error
     * @return {*}
     * @private
     */
    _onResponseError(error) {
        switch (error.response.status) {
            case 0: {
                return this._onConnectionError(error);
            }
            case HTTP_STATUS.UNAUTHORIZED: {
                return this._onUnauthenticatedError(error);
            }
            case HTTP_STATUS.NOT_FOUND: {
                return this._onNotFoundError(error);
            }
            case HTTP_STATUS.UNPROCESSABLE_ENTITY: {
                return this._onValidationError(error);
            }
            default: {
                return this._onHttpError(error);
            }
        }
    }

    /**
     * @private
     * @return {*}
     * @private
     */
    _onSyntaxError(error) {
        const title = "Invalid response type.";
        this._alert.error(title);
        throw error;
    }

    /**
     * @param {Error} error
     * @return {*}
     * @private
     */
    _onConnectionError(error) {
        this._alert.error("Remote server connection error. Please, check your internet connection.");
        throw error;
    }

    /**
     * @param {Error} error
     * @return {*}
     * @private
     */
    _onUnauthenticatedError(error) {
        router.push({name: "sign-out"});
        return this._onHttpError(error);
    }

    /**
     * @param {Error} error
     * @return {*}
     * @private
     */
    _onNotFoundError(error) {
        this._alert.error(error.response.data.message);
        throw error;
    }

    /**
     * @param {Error} error
     * @return {*}
     * @private
     */
    _onValidationError(error) {
        const errors = error.response.data.errors || {};
        Object.keys(errors).forEach((attribute) => {
            errors[attribute].forEach((message) => this._alert.warning(message));
        });
        throw error;
    }

    /**
     * @param {Error} error
     * @return {*}
     * @private
     */
    _onHttpError(error) {
        const title = error.response.data.message || error.response.statusText;
        const status = error.response.status;
        this._alert.error(title, `HTTP ${status} Error.`);
        throw error;
    }
}
