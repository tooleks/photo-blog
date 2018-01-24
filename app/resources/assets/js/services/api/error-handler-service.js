import notification from "../notification";
import router from "../../router";
import {value} from "../../utils";

export const HTTP_STATUS_UNAUTHORIZED = 401;
export const HTTP_STATUS_NOT_FOUND = 404;
export const HTTP_STATUS_UNPROCESSABLE_ENTITY = 422;
export const HTTP_STATUS_NO_CONTENT = 204;

export default class ErrorHandlerService {
    handle(error, options) {
        switch (value(() => error.response.status)) {
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

    handleUnknownError(error, options = {}) {
        notification.error("Remote server connection error. Try again later.");
        return Promise.reject(error);
    }

    handleUnauthenticatedError(error, options = {}) {
        router.push({name: "sign-out"});
        this.handleHttpError(error);
        return Promise.reject(error);
    }

    handleNotFoundError(error, options = {}) {
        if (!options.suppressNotFoundErrors) {
            notification.error(error.response.data.message);
        }
        return Promise.reject(error);
    }

    handleValidationError(error, options = {}) {
        const errors = value(() => error.response.data.errors) || {};
        for (let attribute in errors) {
            if (errors.hasOwnProperty(attribute)) {
                errors[attribute].forEach((message) => notification.warning(message));
            }
        }
        return Promise.reject(error);
    }

    handleHttpError(error, options = {}) {
        if (error instanceof SyntaxError) {
            const title = "Invalid response type.";
            notification.error(title);
        } else if (value(() => error.response)) {
            const title = value(() => error.response.data.message) || "Internal Server Error.";
            const status = value(() => error.response.status) || "500";
            notification.error(title, `HTTP ${status} Error.`);
        } else {
            const title = value(() => error.message) || "Unknown Error.";
            notification.error(title);
        }
        return Promise.reject(error);
    }
}
