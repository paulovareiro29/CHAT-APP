import { useState } from "react";
import { useHistory } from "react-router-dom";
import { Auth } from "../../Auth";
import { LoginForm } from "../../components/LoginForm/LoginForm";
import { SignupForm } from "../../components/SignupForm/SignupForm";

export const Landing = () => {
  const history = useHistory();

  const [window, setWindow] = useState("")
  
  const [loginDefaultValues, setLoginDefaultValues] = useState({})

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
    if(e && e.username && e.password){
      setLoginDefaultValues(e)
    }
  }

  return (
    <div id="landing" className={window}>
      <div className="login">
        <LoginForm changeWindow={changeWindow} defaultValues={loginDefaultValues}/>
      </div>
      <div className="signup">
        <SignupForm changeWindow={changeWindow}/>
      </div>
    </div>
  );
};
