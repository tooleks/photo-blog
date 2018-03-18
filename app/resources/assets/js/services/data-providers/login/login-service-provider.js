import LoginService from "./login-service";
import apiService from "../../api";
import authService from "../../auth";
import mapperService from "../../mapper";

export default function () {
    return new LoginService(apiService, authService, mapperService);
}
