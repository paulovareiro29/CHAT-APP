import { BrowserRouter, Route } from "react-router-dom";
import PopupContainer from "./components/Popup/Popup";
import ProtectedRoute from "./components/ProtectedRoute/ProtectedRoute";
import { ChatApp } from "./pages/ChatApp/ChatApp";
import { Landing } from "./pages/Landing/Landing";
import { Testpage } from "./pages/TestPage";

import "./styles/global.scss";

function App() {
  return (
    <>
      <PopupContainer>
        <div className="app">
          <BrowserRouter>
            <Route exact path="/" component={Landing} />
            <Route exact path="/test" component={Testpage} />
            <ProtectedRoute path="/chat" component={ChatApp} />
          </BrowserRouter>
        </div>
      </PopupContainer>
    </>
  );
}

export default App;
