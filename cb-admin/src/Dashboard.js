// in src/Dashboard.js
import React from 'react';
import Card from '@material-ui/core/Card';
import CardContent from '@material-ui/core/CardContent';
import CardHeader from '@material-ui/core/CardHeader';
import Notificator from "./Notificator";

export default () => (
    <Card>
        <CardHeader title="Welcome to the ACME AC Units admin Console prototype" />
        <CardContent>
				Acme Corp builds AC units. Admin panel is to manage their system..

				<p><b>This project is a proof of concept of a backend system which integrates with all of their units and provides them with an </b></p>

            <Notificator/>

        </CardContent>
    </Card>
);