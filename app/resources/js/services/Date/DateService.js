import moment from "moment";

/**
 * Class DateService.
 */
export default class DateService {
    /**
     * DateService constructor.
     */
    constructor() {
        this.format = this.format.bind(this);
    }

    /**
     * Format datetime string.
     *
     * @param {string} datetime
     * @param {string} [format="LLLL"]
     * @return {*}
     */
    format(datetime, format = "LLLL") {
        return moment.utc(datetime).format(format);
    }
}
