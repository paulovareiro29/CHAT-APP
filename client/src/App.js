import { BrowserRouter, Route } from "react-router-dom";
import ProtectedRoute from "./components/ProtectedRoute/ProtectedRoute";
import { ChatApp } from "./pages/ChatApp/ChatApp";
import { Landing } from "./pages/Landing/Landing";
import { Testpage } from "./pages/TestPage";

import './styles/global.scss'

function App() {
  return (
    <>
      <div className="app">
        <BrowserRouter>
          <Route exact path="/" component={Landing} />
          <Route exact path="/test" component={Testpage} />
          <ProtectedRoute path="/chat" component={ChatApp} />
        </BrowserRouter>
      </div>
    </>
  );
}

export default App;
