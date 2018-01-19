import moment from "moment";
import DateService from "./date-service";

export default function () {
    return new DateService(moment);
}
