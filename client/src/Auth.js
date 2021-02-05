const setToken = (token) => {
  localStorage.setItem(
    "token",
    JSON.stringify({
      token: token.token,
      expDate: token.expDate,
    })
  );
};

const getToken = () => {
  const token = JSON.parse(localStorage.getItem("token"));
  return token;
};

const removeToken = () => {
  if (getToken()) {
    localStorage.removeItem("token");
    return true;
  }

  return false;
};



export const Auth = {
  setToken: setToken,
  getToken: getToken,
  removeToken: removeToken
};
