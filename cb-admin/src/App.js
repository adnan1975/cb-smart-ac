// in src/App.js
import React from 'react';
import {Admin, Resource,ShowGuesser} from 'react-admin';
import simpleRestProvider from 'ra-data-simple-rest';
import Dashboard from './Dashboard';
import authProvider from './authProvider';

import {DeviceList, DeviceShow} from './devices';
import {AdminList} from './admins';


const dataProvider = simpleRestProvider('http://104.248.180.30/api/v1');


const App = () => (
    <Admin  dashboard={Dashboard} authProvider={authProvider} dataProvider={dataProvider}>
        <Resource name="devices" list={DeviceList} show={DeviceShow} />
        <Resource name="admins" list={AdminList} show={ShowGuesser} />
    </Admin>
);
export default App;
