import { forwardRef } from "react";

export const PasswordField = forwardRef(
  ({ name = "", placeholder = "", errors = null }, ref) => {

    return (
      <div className="pv-input">
        <div className={"input " + (errors ? "invalid" : "")}>
          <input
            type="password"
            className="input-field"
            placeholder={placeholder}
            name={name}
            ref={ref}
          />
          <label htmlFor={name} className="placeholder">
            {placeholder}
          </label>
        </div>
        <span className="input-error">{errors ? errors.message : ''}</span>
      </div>
    );
  }
);
