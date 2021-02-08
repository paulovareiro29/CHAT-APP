import { UserAPI } from "../../API/UserAPI";
import { useForm, useWatch } from "react-hook-form";
import { TextField } from "../Input/TextField";
import { PasswordField } from "../Input/PasswordField";
import { Button } from "../Button/Button";
import { useRef, useState } from "react";
import { Shake } from "../Shake/Shake";
import { usePopup } from "../Popup/Popup";

export const SignupForm = ({ changeWindow , defaultValues}) => {
  const popup = usePopup()
  const { register, errors, control, handleSubmit } = useForm({defaultValues: defaultValues});

  const password = useRef({});
  password.current = useWatch({ name: "password", control });

  const [userAlreadyExists, setUserAlreadyExists] = useState(null)
  
  const signup = async (data) => {

    UserAPI.signup(
      data,
      (res) => {
        popup("bottomLeft", {
          title: "Created new account!",
          type: "success",
          body: "You have created a new account successfuly!",
        })
        changeWindow({username: data.username, password: data.password})
      },
      (err) => {

        let message = err.body[0];
        if (message.includes("Username")) {
          setUserAlreadyExists({ message: "- Username already exists" });
        } else {
          setUserAlreadyExists(null);
        }
        popup("bottomLeft", {
          title: "Error",
          type: "danger",
          body: "ERROR",
        })
      }
    );
  };

  return (
    <div id="login-form">
      <div className="login-form-header">
        <span>Create your account!</span>
      </div>

      <div className="login-form-body">
        <form onSubmit={handleSubmit(signup)}>
          <Shake shake={errors.username || userAlreadyExists}>
            <TextField
              name="username"
              placeholder="Username"
              errors={errors.username || userAlreadyExists}
              ref={register({
                required: { value: true, message: "* This field is required" },
              })}
            />
          </Shake>
          <Shake shake={errors.name}>
            <TextField
              name="name"
              placeholder="Name"
              errors={errors.name}
              ref={register({
                required: { value: true, message: "* This field is required" },
              })}
            />
          </Shake>
          <Shake shake={errors.password}>
            <PasswordField
              name="password"
              placeholder="Password"
              errors={errors.password}
              ref={register({
                required: { value: true, message: "* This field is required" },
              })}
            />
          </Shake>
          <Shake shake={errors.confirm_password}>
            <PasswordField
              name="confirm_password"
              placeholder="Confirm Password"
              errors={errors.confirm_password}
              ref={register({
                validate: (value) =>
                  value === password.current || "* Passwords do not match"
              })}
            />
          </Shake>
          <Button type="submit" className="primary login-button">
            Sign Up
          </Button>
        </form>
      </div>

      <div className="login-form-footer">
        <span>Already have an account?</span>
        <Button onClick={changeWindow} className="outline-primary">
          Log In
        </Button>
      </div>
    </div>
  );
};
