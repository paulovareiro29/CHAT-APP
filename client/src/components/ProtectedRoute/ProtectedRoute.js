import { React, useState } from "react";
import { Redirect, Route } from "react-router-dom";
import { UserAPI } from "../../API/UserAPI";
import { Auth } from "../../Auth";

export default function ProtectedRoute({
  component: Component,
  callback = () => {},
  ...rest
}) {
  const [isAuthenticated, setAuthenticated] = useState({ status: "loading" });

  callback = callback.bind(this);

  function validateToken() {
    let token = Auth.getToken();
    if (!token){
      token = null;
    }else{
      token = token.token
    }
    UserAPI.validateToken(
      token,
      (res) => {
        console.log(res);
        setAuthenticated({ status: "authenticated" });
      },
      (err) => {
        console.log(err);
        if (Auth.getToken()) {
          Auth.removeToken();
        }
        setAuthenticated({ status: "unauthenticated" }); //da update com unauthenticated, ele vai dar o redirect
        setAuthenticated({ status: "loading" }); //depois passa para loading
      }
    );
  }

  return (
    <Route
      {...rest}
      render={(props) => {
        if (isAuthenticated.status === "loading") {
          validateToken();
          return <h1>Loading</h1>;
        } else if (isAuthenticated.status === "authenticated") {
          return <Component {...props} />;
        } else if (isAuthenticated.status === "unauthenticated") {
          return (
            <Redirect
              to={{
                pathname: "/",
                state: {
                  from: props.location,
                },
              }}
            />
          );
        }
      }}
    />
  );
}
