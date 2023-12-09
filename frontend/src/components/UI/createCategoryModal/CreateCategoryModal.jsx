import React, {useState} from 'react';
import {Button, Form, Modal} from "react-bootstrap";
import CategoryService from "../../../services/CategoryService";

const CreateCategoryModal = ({show, handleClose, handleOpen, setIsLoading, reloadCategories}) => {
    const [name, setName] = useState('');

    const handleSubmit = (e) => {
        e.preventDefault();

        setIsLoading(true);
        CategoryService.create(name)
            .then(response => {
                console.log(response);
                reloadCategories();
            }).finally(() => {
                handleClose();
                setIsLoading(false);
        });
    };

    return (
        <Modal show={show} onHide={handleClose}>
            <Modal.Header closeButton>
                <Modal.Title>Create category</Modal.Title>
            </Modal.Header>

            <Modal.Body>
                <Form>
                    <Form.Group>
                        <Form.Label>Category name</Form.Label>
                        <Form.Control
                            type="text"
                            name="name"
                            placeholder="Name"
                            value={name}
                            onChange={(e) => setName(e.target.value)}
                        />
                    </Form.Group>
                </Form>
            </Modal.Body>

            <Modal.Footer>
                <Button variant="secondary" onClick={handleClose}>Close</Button>
                <Button onClick={handleSubmit}>Create</Button>
            </Modal.Footer>
        </Modal>
    );
};

export default CreateCategoryModal;