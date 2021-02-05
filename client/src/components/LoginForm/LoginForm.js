import { useHistory } from "react-router-dom";
import { UserAPI } from "../../API/UserAPI";
import { useForm } from "react-hook-form";
import { TextField } from "../../components/Input/TextField";
import { PasswordField } from "../../components/Input/PasswordField";
import { Button } from "../../components/Button/Button";
import { useState } from "react";
import { Shake } from "../Shake/Shake";

export const LoginForm = () => {
  const history = useHistory();

  const { register, errors, handleSubmit } = useForm();

  const [wrongUsername, setWrongUsername] = useState(null);
  const [wrongPassword, setWrongPassword] = useState(null);

  const login = async (data) => {
    await UserAPI.login(
      { username: data.username, password: data.password },
      async (res) => {
        console.log("SUCESS LOGIN");
        //console.log("NEW TOKEN: ", res.body.token);
        history.push("/chat");
      },
      (err) => {
        let message = err.body[0];
        if (message.includes("Username")) {
          setWrongUsername({ message: "- Username doesn't exist" });
        } else {
          setWrongUsername(null);
        }
        if (message.includes("Password")) {
          setWrongPassword({ message: "- Wrong password" });
        } else {
          setWrongPassword(null);
        }
      }
    );
  };

  return (
    <div id="login-form">
      <div className="login-form-header">
        <span>Welcome!</span>
      </div>

      <div className="login-form-body">
        <form onSubmit={handleSubmit(login)}>
          <Shake shake={errors.username || wrongUsername}>
            <TextField
              name="username"
              placeholder="Username"
              errors={errors.username || wrongUsername}
              ref={register({
                required: { value: true, message: "* This field is required" },
              })}
            />
          </Shake>
          <Shake shake={errors.password || wrongPassword}>
            <PasswordField
              name="password"
              placeholder="Password"
              errors={errors.password || wrongPassword}
              ref={register({
                required: { value: true, message: "* This field is required" },
              })}
            />
          </Shake>
          <Button type="submit" className="primary login-button">
            Log In
          </Button>
        </form>
      </div>

      <div className="login-form-footer">
        <span>Don't have an account?</span>
        <Button className="outline-primary">Sign Up</Button>
      </div>
    </div>
  );
};
