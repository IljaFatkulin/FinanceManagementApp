import axios from "../configs/axiosConfig";

export default class UserService {
    static async register(email, password, firstname, lastname) {
        return await axios.post('/users', {
            email: email,
            password: password,
            firstname: firstname,
            lastname: lastname
        });
    }

    static async login(email, password) {
        return await axios.post('/authorize', {
            email: email,
            password: password
        });
    }

    static async loginWithToken(token) {
        return await axios.post('/authorize/token', {
            token: token
        });
    }

    static async forgotPassword(email) {
        return await axios.post('/password/reset', {
            email: email
        });
    }

    static async forgotPasswordVerify(email, newPassword, code) {
        return await axios.post('/password/reset/confirm', {
            email: email,
            new_password: newPassword,
            verification_code: code
        });
    }

    static async changePassword(email, oldPassword, newPassword) {
        return await axios.post('/password/change', {
            email: email,
            new_password: newPassword,
            old_password: oldPassword
        });
    }
}