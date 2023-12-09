import axios from "../configs/axiosConfig";

export default class CategoryService {
    static async getAllUserCategories() {
        return axios.get('/categories');
    }

    static async create(name) {
        return axios.post('/category', {
            name: name
        });
    }

    static async rename(id, newName) {
        return axios.post('/category/rename', {
            id: id,
            new_name: newName
        });
    }

    static async delete(id) {
        return axios.post('/category/delete', {
            id: id
        });
    }
}