import { useState } from "react";
import { Button } from "../components/Button/Button";
import { Shake } from "../components/Shake/Shake";
import "../styles/testpage/testpage.scss";

export const Testpage = () => {

  
  

  return (
    <div className="testpage">
      <Shake shakeByParent>
        ola
      </Shake>
    </div>
  );
};
