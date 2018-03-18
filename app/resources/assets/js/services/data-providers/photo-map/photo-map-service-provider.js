import PhotoMapService from "./photo-map-service";
import apiService from "../../api";
import mapperService from "../../mapper";

export default function () {
    return new PhotoMapService(apiService, mapperService);
}
