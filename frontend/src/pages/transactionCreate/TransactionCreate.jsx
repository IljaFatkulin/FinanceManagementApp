import React, {useEffect, useState} from 'react';
import {TRANSACTION_TYPES} from "../../constants/constants";
import TransactionForm from "../../components/transactionForm/TransactionForm";
import {getCurrentDateTimeForInput} from "../../util/dateUtil";
import CategoryService from "../../services/CategoryService";
import Loader from "../../components/UI/loader/Loader";
import TransactionService from "../../services/TransactionService";
import {useSelector} from "react-redux";
import {useNavigate} from "react-router-dom";
import {Col, Container} from "react-bootstrap";

const TransactionCreate = () => {
    const [form, setForm] = useState({
        type: TRANSACTION_TYPES.EXPENSE,
        amount: '0.00',
        description: '',
        transactionDate: getCurrentDateTimeForInput(),
        category: ''
    });
    const [isLoading, setIsLoading] = useState(true);
    const [categories, setCategories] = useState([]);
    const userData = useSelector(state => state.user);
    const navigate = useNavigate();

    useEffect(() => {
        CategoryService.getAllUserCategories()
            .then(response => {
                setCategories(response.data);
                if(response.data.length) {
                    setForm(prevForm => ({
                        ...prevForm,
                        category: response.data[0].id
                    }));
                } else {
                    navigate('/transactions');
                    alert("You must to create category first");
                }
            }).finally(() => {
                setIsLoading(false);
        });
    }, []);

    const handleSubmit = () => {
        console.log(form);
        setIsLoading(true);
        TransactionService.create(userData.id, form.category, form.type, form.amount, form.description, form.transactionDate)
            .then(response => {
                console.log(response);
                navigate(`/transactions/${response.data.id}`);
            }).finally(() => {
                setIsLoading(false);
        });
    };

    return (
        isLoading
        ?
        <Loader/>
        :
        <Container className="pt-3">
            <TransactionForm handleSubmit={handleSubmit} form={form} setForm={setForm} categories={categories}/>
        </Container>
    );
};

export default TransactionCreate;