import React, {useState} from 'react';
import CategoryService from "../../services/CategoryService";

const CreateCategory = ({setIsLoading, reloadCategories}) => {
    const [name, setName] = useState('');

    const handleSubmit = (e) => {
        e.preventDefault();

        setIsLoading(true);
        CategoryService.create(name)
            .then(response => {
                console.log(response);
                reloadCategories();
            }).finally(() => {
                setIsLoading(false);
        });
    };

    return (
        <div>
            <form onSubmit={handleSubmit}>
                <input
                    type="text"
                    name="name"
                    placeholder="Name"
                    value={name}
                    onChange={(e) => setName(e.target.value)}
                />

                <button type="submit">Submit</button>
            </form>
        </div>
    );
};

export default CreateCategory;