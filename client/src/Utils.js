const isFunction = value => value && (Object.prototype.toString.call(value) === "[object Function]" || "function" === typeof value || value instanceof Function);

const sleep = (ms) => {
    return new Promise(resolve => setTimeout(resolve, ms));
}

export const Utils = {
    isFunction: isFunction,
    sleep: sleep
}