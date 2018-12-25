import * as services from "../services/factory";

export default function (value, ...params) {
    if (!value) return "";
    value = value.toString();
    return services.getLang(value, ...params);
};
