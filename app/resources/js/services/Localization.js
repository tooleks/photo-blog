export default class Localization {
    /**
     * @param {Object} lang
     * @param {string} locale
     */
    constructor(lang, locale) {
        this._lang = lang;
        this._locale = locale;
        this.get = this.get.bind(this);
        this._fillParams = this._fillParams.bind(this);
    }

    /**
     * @param {string} key
     * @param {...string} params
     * @returns {string}
     */
    get(key, ...params) {
        let value = this._lang[this._locale][key];
        if (!value) {
            console.warn(`Undefined localization key: "${key}".`);
            value = key;
        }
        return this._fillParams(value, ...params);
    }

    /**
     * Fill placeholder parameters like {placeholder} with real values.
     *
     * @param {string} value
     * @param {...string} params
     * @returns {string}
     */
    _fillParams(value, ...params) {
        return params.reduce((value, param) => value.replace(/\{[^\{\}]+\}/, param), value);
    }
}
