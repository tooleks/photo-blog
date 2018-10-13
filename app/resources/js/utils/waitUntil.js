const DEFAULT_INTERVAL = 0; // 0 seconds
const DEFAULT_TIMEOUT = 10 * 1000; // 10 seconds

/**
 * Provide promise that will be resolved when callback will return truthy value.
 *
 * @param {Function} callback
 * @param {number} interval
 * @param {number} timeout
 * @return {Promise<*>}
 */
export default function (callback, interval = DEFAULT_INTERVAL, timeout = DEFAULT_TIMEOUT) {
    return new Promise((resolve, reject) => {
        const intervalId = setInterval(async () => {
            try {
                const result = await callback();
                if (result) {
                    clearInterval(intervalId);
                    resolve(result);
                }
            } catch (error) {
                reject(error);
            }
        }, interval);

        setTimeout(() => clearInterval(intervalId), timeout);
    });
}
