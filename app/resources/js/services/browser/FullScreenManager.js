export default class FullScreenManager {
    /**
     * @constructor
     */
    constructor() {
        this.request = this.request.bind(this);
        this.close = this.close.bind(this);
    }

    /**
     * Request the fullscreen mode.
     *
     * @param {HTMLElement} element
     * @return {void}
     */
    request(element = document.documentElement) {
        if (element.requestFullscreen) {
            element.requestFullscreen();
        } else if (element.mozRequestFullScreen) { /* Firefox */
            element.mozRequestFullScreen();
        } else if (element.webkitRequestFullscreen) { /* Chrome, Safari and Opera */
            element.webkitRequestFullscreen();
        } else if (element.msRequestFullscreen) { /* IE/Edge */
            element.msRequestFullscreen();
        }
    }

    /**
     * Close the fullscreen mode.
     *
     * @return {void}
     */
    close() {
        if (document.exitFullscreen) {
            document.exitFullscreen();
        } else if (document.mozCancelFullScreen) { /* Firefox */
            document.mozCancelFullScreen();
        } else if (document.webkitExitFullscreen) { /* Chrome, Safari and Opera */
            document.webkitExitFullscreen();
        } else if (document.msExitFullscreen) { /* IE/Edge */
            document.msExitFullscreen();
        }
    }
}
