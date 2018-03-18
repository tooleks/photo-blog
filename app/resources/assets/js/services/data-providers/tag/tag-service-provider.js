import TagService from "./tag-service";
import apiService from "../../api";
import mapperService from "../../mapper";

export default function () {
    return new TagService(apiService, mapperService);
}
