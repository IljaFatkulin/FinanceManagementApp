import React, {useEffect, useState} from 'react';
import Loader from "../../components/UI/loader/Loader";
import {useSelector} from "react-redux";
import {Button, Col, Container, FormSelect, Row} from "react-bootstrap";
import ShowTransactions from "../../components/showTransactions/ShowTransactions";
import CreateCategoryModal from "../../components/UI/createCategoryModal/CreateCategoryModal";
import {useFetchTransactions} from "../../hooks/useFetchTransactions";
import {useFetchCategories} from "../../hooks/useFetchCategories";
import {useFetchTransactionSums} from "../../hooks/useFetchTransactionSums";
import PieDiagram from "../../components/pieDiagram/PieDiagram";

const Transactions = () => {
    const [isLoading, setIsLoading] = useState(true);
    const [selectedCategory, setSelectedCategory] = useState('all');

    const [show, setShow] = useState(false);
    const handleCloseModal = () => setShow(false);
    const handleOpenModal = () => setShow(true);

    const userData = useSelector(state => state.user);

    const {transactions, isLoading: isTransactionsLoading} = useFetchTransactions(userData, selectedCategory);
    const {categories, isLoading: isCategoriesLoading, refreshCategories} = useFetchCategories();
    const {incomeSum, expenseSum, isLoading: isTransactionSumsLoading} = useFetchTransactionSums(userData);

    useEffect(() => {
        setIsLoading(isTransactionsLoading || isCategoriesLoading || isTransactionSumsLoading);
    }, [isTransactionsLoading, isCategoriesLoading, isTransactionSumsLoading]);

    return (
        isLoading
        ?
        <Loader/>
        :
        <div className="container">
            <div className="container d-flex gap-2 mt-2 mb-2">
                <FormSelect className="w-25" name="category" value={selectedCategory} onChange={(e) => setSelectedCategory(e.target.value)}>
                    <option value="all">All</option>
                    {categories.length && categories.map(category =>
                        <option key={category.id} value={category.id}>{category.name}</option>
                    )}
                </FormSelect>
                <Button variant="primary" onClick={handleOpenModal}>
                    Create category
                </Button>
            </div>

            <CreateCategoryModal show={show} handleOpen={handleOpenModal} handleClose={handleCloseModal} setIsLoading={setIsLoading} reloadCategories={refreshCategories}/>

            <Container fluid>
                <Row>
                    <Col md={6} className="py-0">
                        <ShowTransactions transactions={transactions} />
                    </Col>
                    <Col md={6} className="text-center">
                        <div>
                            <h3>Income</h3>
                            <PieDiagram data={incomeSum}/>
                        </div>
                        <div>
                            <h3>Expense</h3>
                            <PieDiagram data={expenseSum}/>
                        </div>
                    </Col>
                </Row>
            </Container>


        </div>
    );
};

export default Transactions;