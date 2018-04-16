import CookiesService from "./cookies-service";

export default function () {
    return new CookiesService(document.cookie);
}
