/**
 * Class DateService.
 */
export default class DateService {
    /**
     * DateService constructor.
     *
     * @param {*} handler
     */
    constructor(handler) {
        this.handler = handler;
    }

    /**
     * Format datetime string.
     *
     * @param {string} datetime
     * @param {string} [format="LLLL"]
     * @return {*}
     */
    format(datetime, format = "LLLL") {
        return this.handler(datetime).format(format);
    }
}
