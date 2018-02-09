export default class DummyReCaptchaService {
    constructor(onVerified) {
        this.onVerified = onVerified;
    }

    execute() {
        this.onVerified.call(this.onVerified);
    }

    render() {
        //
    }

    reset() {
        //
    }
}
