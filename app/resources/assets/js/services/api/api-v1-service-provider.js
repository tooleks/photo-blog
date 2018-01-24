import axios from "axios";
import ApiService from "./api-service";
import ErrorHandlerService from "./error-handler-service";
import {HTTP_STATUS_NO_CONTENT} from "./error-handler-service";
import config from "../../config";

export default function () {
    const errorHandler = new ErrorHandlerService;
    return new ApiService(
        axios,
        config.url.api,
        (response) => {
            if (response.status === HTTP_STATUS_NO_CONTENT || typeof response.data === "object") {
                return Promise.resolve(response);
            } else {
                return Promise.reject(new SyntaxError);
            }
        },
        (error, options) => {
            return errorHandler.handle(error, options);
        });
}
