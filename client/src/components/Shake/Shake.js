import { useEffect, useState } from "react";

export const Shake = ({ shake, children }) => {

  const [shaking, setShaking] = useState();


  useEffect(() => {
    if (shake) {
        setShaking("shaking");
      setTimeout(() => {
        setShaking("stopped");
      }, 500);
    }
  }, [shake]);

  return <div className={"shake " + shaking}>{children}</div>;
};
