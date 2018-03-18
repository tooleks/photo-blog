import PhotoService from "./photo-service";
import apiService from "../../api";
import mapperService from "../../mapper";

export default function () {
    return new PhotoService(apiService, mapperService);
}
