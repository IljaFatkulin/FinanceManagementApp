import React, {useState} from 'react';
import {handleChange} from "../../util/formUtil";
import UserService from "../../services/UserService";
import Loader from "../../components/UI/loader/Loader";
import {useNavigate} from "react-router-dom";
import {Button, Form, FormControl} from "react-bootstrap";

const ForgotPassword = () => {
    const [form, setForm] = useState({
        email: '',
        newPassword: '',
        code: ''
    });
    const [isLoading, setIsLoading] = useState(false);
    const [isSecondFormVisible, setIsSecondFormVisible] = useState(false);
    const handleFormChange = handleChange(form, setForm);
    const navigate = useNavigate();

    const handleSubmit = (e) => {
        e.preventDefault();

        setIsLoading(true);
        UserService.forgotPassword(form.email)
            .then(response => {
                if(response.status === 200) {
                    setIsSecondFormVisible(true);
                }
            }).finally(() => {
                setIsLoading(false);
        });
    };

    const handleSubmitVerifyForm = (e) => {
        e.preventDefault();

        setIsLoading(true);
        UserService.forgotPasswordVerify(form.email, form.newPassword, form.code)
            .then(response => {
                console.log(response);
                if(response.status === 200) {
                    navigate('/login');
                }
            }).finally(() => setIsLoading(false));
    };

    const handleCancel = (e) => {
        e.preventDefault();
        navigate('/login');
    }

    return (
        <div>
            {isLoading
            ?
                <Loader/>
            :
            <div className="d-flex justify-content-center align-items-center vh-100">
                {isSecondFormVisible
                ?
                <Form className="d-flex flex-column gap-2">
                    <FormControl
                        type="text"
                        name="code"
                        placeholder="Code"
                        value={form.code}
                        onChange={handleFormChange}
                    />

                    <Button onClick={handleSubmitVerifyForm}>Submit</Button>
                </Form>
                :
                <Form className="d-flex flex-column gap-2">
                    <FormControl
                        type="text"
                        name="email"
                        placeholder="Email"
                        value={form.email}
                        onChange={handleFormChange}
                    />

                    <FormControl
                        type="text"
                        name="newPassword"
                        placeholder="New password"
                        value={form.newPassword}
                        onChange={handleFormChange}
                    />

                    <div className="d-flex justify-content-between">
                        <Button variant="secondary" onClick={handleCancel}>Cancel</Button>
                        <Button onClick={handleSubmit}>Submit</Button>
                    </div>
                </Form>
                }
            </div>
            }
        </div>
    );
};

export default ForgotPassword;