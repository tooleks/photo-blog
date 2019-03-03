export default class FullScreenManager {
    /**
     */
    constructor() {
        this.request = this.request.bind(this);
        this.close = this.close.bind(this);
    }

    /**
     * Request the fullscreen mode.
     *
     * @param {HTMLElement} [element]
     * @returns {void}
     */
    request(element = document.documentElement) {
        if (element.requestFullscreen) {
            element.requestFullscreen();
        }
        // Firefox
        else if (element.mozRequestFullScreen) {
            element.mozRequestFullScreen();
        }
        // Chrome, Safari and Opera
        else if (element.webkitRequestFullscreen) {
            element.webkitRequestFullscreen();
        }
        // IE/Edge
        else if (element.msRequestFullscreen) {
            element.msRequestFullscreen();
        }
    }

    /**
     * Close the fullscreen mode.
     *
     * @returns {void}
     */
    close() {
        if (document.exitFullscreen) {
            document.exitFullscreen();
        }
        // Firefox
        else if (document.mozCancelFullScreen) {
            document.mozCancelFullScreen();
        }
        // Chrome, Safari and Opera
        else if (document.webkitExitFullscreen) {
            document.webkitExitFullscreen();
        }
        // IE/Edge
        else if (document.msExitFullscreen) {
            document.msExitFullscreen();
        }
    }
}
