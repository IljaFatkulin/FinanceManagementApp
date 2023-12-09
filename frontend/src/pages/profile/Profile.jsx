import React, {useState} from 'react';
import {useSelector} from "react-redux";
import {handleChange} from "../../util/formUtil";
import UserService from "../../services/UserService";
import Loader from "../../components/UI/loader/Loader";

const Profile = () => {
    const [changePasswordForm, setChangePasswordForm] = useState({
        oldPassword: '',
        newPassword: ''
    });
    const [isLoading, setIsLoading] = useState(false);

    const userData = useSelector(state => state.user);

    const handleFormChange = handleChange(changePasswordForm, setChangePasswordForm);

    const handleChangePassword = (e) => {
        e.preventDefault();
        setIsLoading(true);
        UserService.changePassword(userData.email, changePasswordForm.oldPassword, changePasswordForm.newPassword)
            .then(response => {
                console.log(response);
            }).finally(() => {
                setIsLoading(false);
        });
    }

    return (
        isLoading
        ?
        <Loader/>
        :
        <div>
            <h1>Profile</h1>
            <p>{userData.email}</p>
            <h3>Change password</h3>
            <form onSubmit={handleChangePassword}>
                <input
                    type="text"
                    name="oldPassword"
                    placeholder="Old password"
                    value={changePasswordForm.oldPassword}
                    onChange={handleFormChange}
                />

                <input
                    type="text"
                    name="newPassword"
                    placeholder="New password"
                    value={changePasswordForm.newPassword}
                    onChange={handleFormChange}
                />

                <button type="submit">Submit</button>
            </form>
        </div>
    );
};

export default Profile;