export default class DateService {
    constructor(handler) {
        this.handler = handler;
    }

    format(datetime, format = "LLLL") {
        return this.handler(datetime).format(format);
    }
}
