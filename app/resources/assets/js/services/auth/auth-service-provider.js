import {EventEmitter} from "tooleks";
import AuthService from "./auth-service";
import storage from "../storage";

export default function () {
    return new AuthService(new EventEmitter, storage, "user");
}
