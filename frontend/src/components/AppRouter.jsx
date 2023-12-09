import React, {useEffect} from 'react';
import {Navigate, Route, Routes} from "react-router-dom";
import {privateRoutes, publicRoutes} from "../routes";
import {useSelector} from "react-redux";
import Navigation from "./UI/navigation/Navigation";

const AppRouter = () => {
    const userData = useSelector(state => state.user);

    return (
        userData.id
        ?
        <Routes>
            {privateRoutes.map(route =>
                <Route
                    element={route.element}
                    path={route.path}
                    key={route.path}
                />
            )}
            <Route path="*" element={<Navigate to="/transactions" replace={true}/>} />
        </Routes>
        :
        <Routes>
            {publicRoutes.map(route =>
                <Route
                    element={route.element}
                    path={route.path}
                    key={route.path}
                />
            )}
            <Route path="*" element={<Navigate to="/login" replace={true} />} />
        </Routes>
    );
};

export default AppRouter;