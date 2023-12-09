import React, {useEffect} from 'react';
import {useCookies} from "react-cookie";
import {useDispatch} from "react-redux";
import {setAllUserData} from "../../store/userActions";
import {useNavigate} from "react-router-dom";

const Logout = () => {
    const [cookies, setCookie, removeCookie] = useCookies();
    const dispatch = useDispatch();
    const navigate = useNavigate();

    useEffect(() => {
        removeCookie('userData');
        dispatch(setAllUserData({}));
        navigate('/login');
    }, []);

    return (
        <div>

        </div>
    );
};

export default Logout;