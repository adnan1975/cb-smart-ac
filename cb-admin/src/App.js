// in src/App.js
import React from 'react';
import {Admin, Resource} from 'react-admin';
import simpleRestProvider from 'ra-data-simple-rest';
import Dashboard from './Dashboard';
import authProvider from './authProvider';

import {DeviceList, DeviceShow} from './devices';
import rate from './measurementReducer';


const dataProvider = simpleRestProvider('http://104.248.180.30/api/v1');


const App = () => (
    <Admin customReducers={{ rate }}  dashboard={Dashboard} authProvider={authProvider} dataProvider={dataProvider}>
        <Resource name="devices" list={DeviceList} show={DeviceShow} />
    </Admin>
);
export default App;
