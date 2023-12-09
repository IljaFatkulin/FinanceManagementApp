import React, {useEffect, useState} from 'react';
import CategoryService from "../../services/CategoryService";
import Loader from "../../components/UI/loader/Loader";
import {handleChange} from "../../util/formUtil";
import {Button, Container, FormControl, FormSelect} from "react-bootstrap";

const ManageCategories = () => {
    const [categories, setCategories] = useState([]);
    const [isLoading, setIsLoading] = useState(true);
    const [selectedCategory, setSelectedCategory] = useState({
        id: '',
        name: ''
    });

    const fetchCategories = () => {
        setIsLoading(true);
        CategoryService.getAllUserCategories()
            .then(response => {
                setCategories(response.data);
            }).finally(() => {
            setIsLoading(false);
        });
    };

    useEffect(() => {
        fetchCategories();
    }, []);

    const handleCategoryChange = (e) => {
        const selectedId = e.target.value;
        const category = categories.find(c => c.id.toString() === selectedId);
        setSelectedCategory(category);
    };

    const handleFormChange = handleChange(selectedCategory, setSelectedCategory);

    const handleRename = () => {
        setIsLoading(true);
        CategoryService.rename(selectedCategory.id, selectedCategory.name)
            .then(response => {
                console.log(response);
                fetchCategories();
            }).finally(() => {
                setIsLoading(false);
        });
    };

    const handleDelete = () => {
        setIsLoading(true);
        CategoryService.delete(selectedCategory.id)
            .then(response => {
                console.log(response);
                setSelectedCategory(prevState => ({
                    ...prevState,
                    name: ''
                }));
                fetchCategories();
            }).finally(() => {
                setIsLoading(false);
        });
    }

    return (
        isLoading
        ?
        <Loader/>
        :
        <Container className="d-flex flex-column gap-2 pt-3 w-25 align-items-center">
            <FormSelect name="category" value={selectedCategory.id} onChange={handleCategoryChange}>
                <option value="" disabled>Category</option>
                {categories.length && categories.map(category =>
                    <option key={category.id} value={category.id}>{category.name}</option>
                )}
            </FormSelect>
            <FormControl
                type="text"
                name="name"
                placeholder="Name"
                value={selectedCategory.name}
                onChange={handleFormChange}
            />

            <Button className="w-25" onClick={handleRename}>Rename</Button>
            <Button variant="danger" className="w-25" onClick={handleDelete}>Delete</Button>
        </Container>
    );
};

export default ManageCategories;