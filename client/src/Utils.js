const isFunction = (value) =>
  value &&
  (Object.prototype.toString.call(value) === "[object Function]" ||
    "function" === typeof value ||
    value instanceof Function);

const sleep = (ms) => {
  return new Promise((resolve) => setTimeout(resolve, ms));
};

const randString = (length) => {
  var result = "";
  var characters =
    "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
  var charactersLength = characters.length;
  for (var i = 0; i < length; i++) {
    result += characters.charAt(Math.floor(Math.random() * charactersLength));
  }
  return result;
};

const breakpoints = {
  mobile: 320,
  tablet: 768,
  laptop: 1600
}

export const Utils = {
  isFunction: isFunction,
  sleep: sleep,
  randString: randString,
  breakpoints: breakpoints
};
