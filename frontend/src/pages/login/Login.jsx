import React, {useState} from 'react';
import {handleChange} from "../../util/formUtil";
import UserService from "../../services/UserService";
import {useDispatch} from "react-redux";
import {setAllUserData} from "../../store/userActions";
import {useCookies} from "react-cookie";
import {Link} from "react-router-dom";
import {Button, Form} from "react-bootstrap";

const Login = () => {
    const [form, setForm] = useState({
       email: '',
       password: '' 
    });

    const dispatch = useDispatch();
    const [cookies, setCookie] = useCookies();

    const handleSubmit = (e) => {
        e.preventDefault();
        UserService.login(form.email, form.password)
            .then(response => {
                if(response.status === 200) {
                    const data = {
                        id: response.data.user.id,
                        email: response.data.user.email,
                        firstname: response.data.user.firstname,
                        lastname: response.data.user.lastname,
                        role: response.data.user.role.name,
                        token: response.data.token
                    }
                    setCookie("userData", data);
                    dispatch(setAllUserData(data));
                }
            });
    }

    const handleFormChange = handleChange(form, setForm);
    
    return (
        <div className="d-flex justify-content-center align-items-center vh-100">
            <div className="row">
                <div className="col-md-auto text-center">
                    <Form onSubmit={handleSubmit}>
                        <Form.Group className="mb-3 text-start">
                            <Form.Label>Email address</Form.Label>
                            <Form.Control
                                type="text"
                                name="email"
                                placeholder="Email"
                                value={form.email}
                                onChange={handleFormChange}
                            />
                            <Form.Text className="text-muted">
                                We'll never share your email with anyone else.
                            </Form.Text>
                        </Form.Group>

                        <Form.Group className="mb-3 text-start">
                            <Form.Label>Password</Form.Label>
                            <Form.Control
                                type="password"
                                name="password"
                                placeholder="Password"
                                value={form.password}
                                onChange={handleFormChange}
                            />
                        </Form.Group>

                        <Button type="submit">Log In</Button>
                    </Form>
                    <Link className="link" to={'/login/reset'}>Forgot password?</Link>
                </div>
            </div>
        </div>
    );
};

export default Login;