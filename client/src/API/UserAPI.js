import { Auth } from "../Auth";
import { Utils } from "../Utils";

const { API } = require("./API");

const login = (data, onSuccess, onFail) => {
  return API.POST(
    "/login",
    {
      username: data.username,
      password: data.password,
    },
    (res) => {
      const token = res.body;
      Auth.setToken({
        token: token.token,
        expDate: token.expDate,
      });


      if (Utils.isFunction(onSuccess)) {
        onSuccess(res);
      }
    },
    (err) => {
      if (Utils.isFunction(onFail)) {
        onFail(err);
      }
    }
  );
};

const signup = (data, onSuccess, onFail) => {
  return API.POST(
    "/user/",
    {
      username: data.username,
      password: data.password,
      name: data.name
    },
    (res) => {
      if (Utils.isFunction(onSuccess)) {
        onSuccess(res);
      }
    },
    (err) => {
      if (Utils.isFunction(onFail)) {
        onFail(err);
      }
    }
  );
} 

const logout = (onSuccess, onFail) => {
  return setTimeout(() => {
    let status = Auth.removeToken();
    if (status) {
      if (Utils.isFunction(onSuccess)) {
        onSuccess();
      }
    } else {
      if (Utils.isFunction(onFail)) {
        onFail();
      }
    }
  }, 100);
};

const validateToken = (token, onSuccess, onFail) => {
  return API.POST(
    "/validateToken",
    {
      token: token,
    },
    onSuccess,
    onFail
  );
};

export const UserAPI = {
  login: login,
  logout: logout,
  signup: signup,
  validateToken: validateToken,
};
