import { Routes, Route } from "react-router-dom"
import NavigationMenu from "./components/pageComponents/NavigationMenu.jsx"
import Footer from "./components/pageComponents/Footer.jsx"
import CountriesPage from "./components/pages/CountriesPage.jsx"
import HomePage from "./components/pages/HomePage.jsx"
import ContentPage from "./components/pages/ContentPage.jsx"
import NotFoundPage from "./components/pages/NotFoundPage.jsx"
import SignIn from "./components/pageComponents/SignIn.jsx"
import UserContext from './helpers/userContext'
import {useState} from "react"
import Note from "./components/pageComponents/Note.jsx";

function App() {
    const [signedIn, setSignedIn] = useState(null)

    return (
      <>
          <UserContext.Provider value={{signedIn, setSignedIn}}>
          <SignIn />
          <NavigationMenu />
          <Routes>
              <Route path="/" element={<HomePage />}/>
              <Route path="/countries" element={<CountriesPage />}/>
              <Route path="/content" element={<ContentPage />}/>
              <Route path="*" element={<NotFoundPage />}/>
          </Routes>
          <Footer />
          </UserContext.Provider>
      </>
  )

}

export default App
