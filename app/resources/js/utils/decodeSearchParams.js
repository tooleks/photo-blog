/**
 * @param {string} search
 * @returns {Object}
 */
export default function decodeSearchParams(search) {
    search = decodeURIComponent(search);

    if (search.startsWith("?")) {
        search = search.slice(1);
    }

    return search
        .split("&")
        .filter((item) => item.length > 0)
        .reduce((params, item) => {
            const [name, value = ""] = item.split("=");
            params[name] = value;
            return params;
        }, {});
}
