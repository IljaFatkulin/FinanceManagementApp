import React from 'react';
import {Button, Container, Nav, Navbar, NavbarCollapse, NavLink} from "react-bootstrap";
import {Link} from "react-router-dom";

const Navigation = () => {
    return (
        <Navbar className="bg-body-tertiary">
            <Container>
                <NavbarCollapse>
                    <Nav className="me-auto">
                        <NavLink as={Link} to="/transactions">Transactions</NavLink>
                        <NavLink as={Link} to="/transactions/create">Create transaction</NavLink>
                        <NavLink as={Link} to="/categories">Manage categories</NavLink>
                    </Nav>
                    <Nav>
                        <NavLink as={Link} to="/logout"><Button>Log out</Button></NavLink>
                    </Nav>
                </NavbarCollapse>
            </Container>
        </Navbar>
    );
};

export default Navigation;