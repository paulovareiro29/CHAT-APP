import { useHistory } from "react-router-dom";
import { UserAPI } from "../../API/UserAPI";

export const ChatApp = () => {
  const history = useHistory();

  const logout = async () => {
    await UserAPI.logout(
      () => {
        history.push("/");
        console.log("SUCCESS LOGOUT");
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
