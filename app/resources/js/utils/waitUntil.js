const DEFAULT_TIMEOUT = 30 * 1000; // 30 seconds
const DEFAULT_INTERVAL = 0; // 0 seconds

/**
 * Provide promise that will be resolved when callback will return truthy value.
 *
 * @param {Function} callback
 * @param {number} interval
 * @param {number} timeout
 * @return {Promise<*>}
 */
export default function (callback, timeout = DEFAULT_TIMEOUT, interval = DEFAULT_INTERVAL) {
    return new Promise((resolve, reject) => {
        const intervalId = setInterval(() => {
            try {
                const result = callback();
                if (result) {
                    clearInterval(intervalId);
                    resolve(result);
                }
            } catch (error) {
                //
            }
        }, interval);

        setTimeout(() => {
            clearInterval(intervalId);
            reject(new Error(`The timeout period (${timeout}ms) elapsed prior to completion of the operation.`));
        }, timeout);
    });
}
