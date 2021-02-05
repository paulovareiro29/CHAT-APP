import { Utils } from "../Utils";

const URL = "http://localhost/chat-app/api";

const requestOptions = {
  method: "",
  headers: {
    "Content-Type": "application/json",
    Token: "get token",
  },
  mode: "cors",
  body: {},
};

const defaultOnSuccess = (res) => {
  console.log(res);
};

const defaultOnFail = (err) => {
  console.log(err);
};

const FETCH = (
  route,
  options,
  onSuccess = defaultOnSuccess,
  onFail = defaultOnFail
) => {
  return fetch(route, options)
    .then((x) => x.json())
    .then(
      (res) => {
        if (res.status < 400) {
          //se nao for erro
          if (Utils.isFunction(onSuccess)) {
            onSuccess(res);
          }
        } else {
          //se for erro
          if (Utils.isFunction(onFail)) {
            onFail(res);
          }
        }
      },
      (err) => {
        if (Utils.isFunction(onFail)) {
          onFail(err);
        }
      }
    );
};

const GET = (route, onSuccess, onFail) => {
  requestOptions.method = "GET";

  return FETCH(URL + route, requestOptions, onSuccess, onFail);
};

const POST = (route, body, onSuccess, onFail) => {
  requestOptions.method = "POST";
  requestOptions.body = JSON.stringify(body);

  return FETCH(URL + route, requestOptions, onSuccess, onFail);
};

const PUT = (route, body, onSuccess, onFail) => {
  requestOptions.method = "PUT";
  requestOptions.body = JSON.stringify(body);

  return FETCH(URL + route, requestOptions, onSuccess, onFail);
};

const DELETE = (route, onSuccess, onFail) => {
  requestOptions.method = "DELETE";

  return FETCH(URL + route, requestOptions, onSuccess, onFail);
};

export const API = {
  URL: URL,
  GET: GET,
  POST: POST,
  PUT: PUT,
  DELETE: DELETE,
};
