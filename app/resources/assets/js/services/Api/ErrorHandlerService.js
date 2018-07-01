import {optional as opt} from "tooleks";
import router from "../../router";

export const HTTP_STATUS_UNAUTHORIZED = 401;
export const HTTP_STATUS_NOT_FOUND = 404;
export const HTTP_STATUS_UNPROCESSABLE_ENTITY = 422;
export const HTTP_STATUS_NO_CONTENT = 204;

/**
 * Class ErrorHandlerService.
 */
export default class ErrorHandlerService {
    /**
     * ErrorHandlerService constructor.
     *
     * @param {NotificationService} notificationService
     */
    constructor(notificationService) {
        this.notificationService = notificationService;
        this.handle = this.handle.bind(this);
        this.handleUnknownError = this.handleUnknownError.bind(this);
        this.handleUnauthenticatedError = this.handleUnauthenticatedError.bind(this);
        this.handleNotFoundError = this.handleNotFoundError.bind(this);
        this.handleValidationError = this.handleValidationError.bind(this);
        this.handleHttpError = this.handleHttpError.bind(this);
    }

    /**
     * Handle error.
     *
     * @param {Error} error
     * @param {Object} options
     * @return {*}
     */
    handle(error, options) {
        switch (opt(() => error.response.status)) {
            case 0: {
                return this.handleUnknownError(error, options);
            }
            case HTTP_STATUS_UNAUTHORIZED: {
                return this.handleUnauthenticatedError(error, options);
            }
            case HTTP_STATUS_NOT_FOUND: {
                return this.handleNotFoundError(error, options);
            }
            case HTTP_STATUS_UNPROCESSABLE_ENTITY: {
                return this.handleValidationError(error, options);
            }
            default: {
                return this.handleHttpError(error, options);
            }
        }
    }

    /**
     * Handle unknown error.
     *
     * @param {Error} error
     * @param {Object} options
     * @return {Promise}
     */
    async handleUnknownError(error, options) {
        this.notificationService.error("Remote server connection error. Try again later.");
        throw error;
    }

    /**
     * Handle unauthenticated error.
     *
     * @param {Error} error
     * @param {Object} options
     * @return {Promise}
     */
    async handleUnauthenticatedError(error, options) {
        router.push({name: "sign-out"});
        this.handleHttpError(error);
        throw error;
    }

    /**
     * Handle not found error.
     *
     * @param {Error} error
     * @param {Object} [options={}]
     * @param {boolean} [options.suppressNotFoundErrors=false]
     * @return {Promise}
     */
    async handleNotFoundError(error, {suppressNotFoundErrors = false} = {}) {
        if (!suppressNotFoundErrors) {
            this.notificationService.error(error.response.data.message);
        }
        throw error;
    }

    /**
     * Handle validation error.
     *
     * @param {Error} error
     * @param {Object} options
     * @return {Promise}
     */
    async handleValidationError(error, {}) {
        const errors = opt(() => error.response.data.errors) || {};
        for (let attribute in errors) {
            if (errors.hasOwnProperty(attribute)) {
                errors[attribute].forEach((message) => this.notificationService.warning(message));
            }
        }
        throw error;
    }

    /**
     * Handle HTTP layer error.
     *
     * @param {Error} error
     * @param {Object} options
     * @return {Promise}
     */
    async handleHttpError(error, options = {}) {
        if (error instanceof SyntaxError) {
            const title = "Invalid response type.";
            this.notificationService.error(title);
        } else if (opt(() => error.response)) {
            const title = opt(() => error.response.data.message) || "Internal Server Error.";
            const status = opt(() => error.response.status) || "500";
            this.notificationService.error(title, `HTTP ${status} Error.`);
        } else {
            const title = opt(() => error.message) || "Unknown Error.";
            this.notificationService.error(title);
        }
        throw error;
    }
}
