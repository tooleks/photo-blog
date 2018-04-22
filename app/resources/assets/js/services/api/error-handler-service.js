import {optional} from "tooleks";
import router from "../../router";

export const HTTP_STATUS_UNAUTHORIZED = 401;
export const HTTP_STATUS_NOT_FOUND = 404;
export const HTTP_STATUS_UNPROCESSABLE_ENTITY = 422;
export const HTTP_STATUS_NO_CONTENT = 204;

export default class ErrorHandlerService {
    constructor(notificationService) {
        this.notificationService = notificationService;
    }

    handle(error, options) {
        switch (optional(() => error.response.status)) {
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

    handleUnknownError(error, {}) {
        this.notificationService.error("Remote server connection error. Try again later.");
        return Promise.reject(error);
    }

    handleUnauthenticatedError(error, {}) {
        router.push({name: "sign-out"});
        this.handleHttpError(error);
        return Promise.reject(error);
    }

    handleNotFoundError(error, {suppressNotFoundErrors = false}) {
        if (!suppressNotFoundErrors) {
            this.notificationService.error(error.response.data.message);
        }
        return Promise.reject(error);
    }

    handleValidationError(error, {}) {
        const errors = optional(() => error.response.data.errors) || {};
        for (let attribute in errors) {
            if (errors.hasOwnProperty(attribute)) {
                errors[attribute].forEach((message) => this.notificationService.warning(message));
            }
        }
        return Promise.reject(error);
    }

    handleHttpError(error, options = {}) {
        if (error instanceof SyntaxError) {
            const title = "Invalid response type.";
            this.notificationService.error(title);
        } else if (optional(() => error.response)) {
            const title = optional(() => error.response.data.message) || "Internal Server Error.";
            const status = optional(() => error.response.status) || "500";
            this.notificationService.error(title, `HTTP ${status} Error.`);
        } else {
            const title = optional(() => error.message) || "Unknown Error.";
            this.notificationService.error(title);
        }
        return Promise.reject(error);
    }
}
