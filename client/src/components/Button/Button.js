import { Ripple } from "../Ripple/Ripple";

export const Button = ({
  type = "button",
  onClick = (e) => {},
  className = "primary",
  children,
}) => {

  return (
    <Ripple color="#FFF">
      <button
        type={type}
        onClick={onClick}
        className={"pv-btn " + className}
      >
        {children}
      </button>
    </Ripple>
  );
};
