export default class DummyReCaptcha {
    constructor(element, siteKey, onVerified) {
        this.element = element;
        this.siteKey = siteKey;
        this.onVerified = onVerified;
    }

    isReady() {
        return true;
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
