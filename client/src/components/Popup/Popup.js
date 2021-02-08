import { createContext, useCallback, useContext, useEffect, useState } from "react";
import { Utils } from "../../Utils";

import { FaCheck, FaExclamation } from "react-icons/fa";
import { TiInfoLarge } from "react-icons/ti";

const popupIcons = {
  primary: <TiInfoLarge />,
  secondary: <TiInfoLarge />,
  danger: <FaExclamation />,
  warning: <FaExclamation />,
  success: <FaCheck />,
};

const PopupContext = createContext(null);

export const usePopup = () => {
  return useContext(PopupContext)
}

export default function PopupContainer({ children }) {
  const [queue, setQueue] = useState([]);
  const [windowSize, setWindowSize] = useState(window.innerWidth);

  useEffect(() => {
    const handleRisize = () => {
      setWindowSize(window.innerWidth);
    };

    window.addEventListener("resize", handleRisize);

    return () => window.removeEventListener("resize", handleRisize);
  }, []);

  const addPopup = (position, info) => {
    setQueue([
      ...queue,
      {
        id: Utils.randString(10),
        position: position,
        info: info,
      },
    ]);
  };

  const removePopup = useCallback(
    (id) => {
      let arr = [];
      queue.forEach((popup, index) => {
        if (popup.id !== id) {
          arr.push(popup);
        }
      });

      setQueue(arr);
    },
    [queue, setQueue]
  );

  const renderQueue = (position) => {
    return queue.map((popup, key) => {
      let pos = popup.position;

      if (windowSize <= Utils.breakpoints.tablet) {
        if (pos.includes("Right")) {
          pos = pos.replace("Right", "Left");
        }
      }

      if (pos === position) {
        return (
          <Popup
            onClose={removePopup}
            key={popup.id}
            id={popup.id}
            info={popup.info}
          />
        );
      }
      return null;
    });
  };
  /*<Button
        className="success"
        onClick={() => {
          addPopup("topRight", {
            title: "Success",
            time: null,
            delay: null,
            type: "warning",
            body: "Body",
          });
        }}
      >
        Popup
      </Button>*/
  return (
    <>
      <PopupContext.Provider value={addPopup}>{children}</PopupContext.Provider>
      <div id="popup-container">
        <div className="zone top left">{renderQueue("topLeft")}</div>
        <div className="zone top right">{renderQueue("topRight")}</div>
        <div className="zone bottom left">{renderQueue("bottomLeft")}</div>
        <div className="zone bottom right">{renderQueue("bottomRight")}</div>
      </div>
    </>
  );
}

const Popup = ({ id, onClose, info = {} }) => {
  const [showing, setShowing] = useState(false);

  const handleClose = useCallback(() => {
    setShowing(false);
    setTimeout(() => {
      onClose(id);
    }, 200);
  }, [onClose, id]);

  useEffect(() => {
    if (showing) {
      if (info && info.time && Number.isInteger(info.time)) {
        const timeOut = setTimeout(() => {
          handleClose();
        }, info.time);
        return () => timeOut && clearTimeout(timeOut);
      }
    }
  }, [showing, id, info, handleClose]);

  useEffect(() => {
    if (info && info.delay && Number.isInteger(info.delay)) {
      const delay = setTimeout(() => {
        setShowing(true);
      }, info.delay);

      return () => delay && clearTimeout(delay);
    } else {
      setShowing(true);
    }
  }, [info]);

  return (
    <div
      id="popup"
      className={(info.type || " primary ") + (showing ? " visible " : "")}
    >
      <span onClick={handleClose} className="popup-close-btn">
        X
      </span>

      <div className="popup-icon-wrapper">
        <span className="popup-icon">{popupIcons[info.type || "primary"]}</span>
      </div>

      <div className="popup-content">
        <div className="popup-title">{info.title}</div>
        <div className="popup-body">{info.body}</div>
      </div>
    </div>
  );
};
