import { useHistory } from "react-router-dom";
import { UserAPI } from "../../API/UserAPI";
import { usePopup } from "../../components/Popup/Popup";

export const ChatApp = () => {
  const history = useHistory();
  const popup = usePopup()

  const logout = async () => {
    await UserAPI.logout(
      () => {
        history.push("/");
        console.log("SUCCESS LOGOUT");
        popup("bottomLeft",{
          type: "secondary",
          title: "Logged Out",
          body: "You are now logged out!"
        })
      },
      () => {
        console.log("USER IS ALREADY LOGGED OUT");
      }
    );
  };

  return (
    <>
      <h1>Chat App</h1>
      <button onClick={logout}>Logout</button>
    </>
  );
};
