import React from 'react';
import {handleAmountChange, handleChange} from "../../util/formUtil";
import {TRANSACTION_TYPES} from "../../constants/constants";
import {Button, Form, FormControl, FormGroup, FormLabel, FormSelect} from "react-bootstrap";

const TransactionForm = ({handleSubmit, form, setForm, categories}) => {
    const onFieldChange = handleChange(form, setForm);
    const onAmountChange = handleAmountChange(form, setForm);

    const onFormSubmit = (e) => {
        e.preventDefault();

        handleSubmit();
    }

    return (
        <Form onSubmit={onFormSubmit} className="d-flex flex-column gap-3">
            <FormGroup>
                <FormLabel>Transaction type</FormLabel>
                <FormSelect name="type" value={form.type} onChange={onFieldChange}>
                    {Object.entries(TRANSACTION_TYPES).map(([key, value]) => (
                        <option key={key} value={value}>{value}</option>
                    ))}
                </FormSelect>
            </FormGroup>

            <FormGroup>
                <FormLabel>Amount</FormLabel>
                <FormControl
                    type="number"
                    name="amount"
                    placeholder="Amount"
                    step="0.01"
                    value={form.amount}
                    onChange={onAmountChange}
                />
            </FormGroup>

            <FormGroup>
                <FormLabel>Description</FormLabel>
                <FormControl
                    type="text"
                    name="description"
                    placeholder="Description"
                    value={form.description}
                    onChange={onFieldChange}
                />
            </FormGroup>

            <FormGroup>
                <FormLabel>Date</FormLabel>
                <FormControl
                    type="datetime-local"
                    name="transactionDate"
                    placeholder="Transaction date"
                    value={form.transactionDate}
                    onChange={onFieldChange}
                />
            </FormGroup>

            {categories &&
                <FormGroup>
                    <FormLabel>Category</FormLabel>
                    {categories && categories.length &&
                        <FormSelect name="category" value={form.category} onChange={onFieldChange}>
                            {categories.map(category =>
                                <option key={category.id} value={category.id}>{category.name}</option>
                            )}
                        </FormSelect>
                    }
                </FormGroup>
            }

            <Button className="w-25 align-self-center" type="submit">Create</Button>
        </Form>
    );
};

export default TransactionForm;