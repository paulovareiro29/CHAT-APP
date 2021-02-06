import { useState } from "react";
import { useHistory } from "react-router-dom";
import { Auth } from "../../Auth";
import { LoginForm } from "../../components/LoginForm/LoginForm";
import { SignupForm } from "../../components/SignupForm/SignupForm";

import LandingIMG from "../../images/landing-bg.svg";

export const Landing = () => {
  const history = useHistory();

  const [window, setWindow] = useState("")
  
  if (Auth.getToken()) {
    console.log("Logout first");
    history.push("/chat");
  }



  const changeWindow = (e) => {
    if(window === "" || window === "login"){
      setWindow("signup")
      return
    }
    setWindow("login")
  }

  return (
    <div id="landing" className={window}>

      <div className="login">
        <LoginForm changeWindow={changeWindow}/>
      </div>
      <div className="signup">
        <SignupForm changeWindow={changeWindow}/>
      </div>
    </div>
  );
};
