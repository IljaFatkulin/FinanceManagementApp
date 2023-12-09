import React, {useEffect, useState} from 'react';
import {BrowserRouter} from "react-router-dom";
import AppRouter from "./components/AppRouter";
import {useCookies} from "react-cookie";
import {useDispatch, useSelector} from "react-redux";
import {setAllUserData} from "./store/userActions";
import UserService from "./services/UserService";
import Loader from "./components/UI/loader/Loader";
import Navigation from "./components/UI/navigation/Navigation";

function App() {
    const [isLoading, setIsLoading] = useState(true);
    const [cookies] = useCookies();
    const userData = useSelector(state => state.user);

    const dispatch = useDispatch();

    useEffect(() => {
        if(cookies['userData']) {
            UserService.loginWithToken(cookies['userData'].token)
                .then(response => {
                    if (response.status === 200) {
                        dispatch(setAllUserData(cookies['userData']));
                    }
                }).catch((error) => {
                    console.log(error);
                }).finally(() => {
                    setIsLoading(false);
            });

        } else {
            setIsLoading(false);
        }
    }, [])

    return (
        <div className="App">
        {isLoading
        ?
            <Loader/>
        :
            <BrowserRouter>
                {userData.id && <Navigation />}
                <AppRouter/>
            </BrowserRouter>
        }
        </div>
    );
}

export default App;