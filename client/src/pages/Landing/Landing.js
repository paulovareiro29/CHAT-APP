import { useHistory } from "react-router-dom";
import { Auth } from "../../Auth";
import { LoginForm } from "../../components/LoginForm/LoginForm";

import LandingIMG from "../../images/landing-bg.svg";

export const Landing = () => {
  const history = useHistory();

  if (Auth.getToken()) {
    console.log("Logout first");
    history.push("/chat");
  }

  return (
    <div id="landing">

      <div className="right">
        <LoginForm />
      </div>
    </div>
  );
};
