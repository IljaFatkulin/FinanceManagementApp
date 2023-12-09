import React, {useState} from 'react';
import {handleChange} from "../../util/formUtil";
import UserService from "../../services/UserService";

const Register = () => {
    const [form, setForm] = useState({
        "email": '',
        "password": '',
        "firstname": '',
        "lastname": ''
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        UserService.register(form.email, form.password, form.firstname, form.lastname)
            .then(response => {
                console.log(response);
            })
    }

    const handleFormChange = handleChange(form, setForm);

    return (
        <div>
            <form onSubmit={handleSubmit}>
                <input
                    type="text"
                    name="email"
                    placeholder="Email"
                    value={form.email}
                    onChange={handleFormChange}
                />

                <input
                    type="text"
                    name="password"
                    placeholder="Password"
                    value={form.password}
                    onChange={handleFormChange}
                />

                <input
                    type="text"
                    name="firstname"
                    placeholder="Firstname"
                    value={form.firstname}
                    onChange={handleFormChange}
                />

                <input
                    type="text"
                    name="lastname"
                    placeholder="Lastname"
                    value={form.lastname}
                    onChange={handleFormChange}
                />

                <button type="submit">Submit</button>
            </form>
        </div>
    );
};

export default Register;