import React from 'react';

import Paper from '@material-ui/core/Paper';
import {
    CartesianGrid,
    Legend,
    ResponsiveContainer,
    Scatter,
    ScatterChart,
    Tooltip,
    XAxis,
    YAxis,
    ZAxis
} from 'recharts';


import moment from 'moment'

export const state = {
    interval: '',
    labelWidth: 0,
};
export const Test = ({measurements}) => (

    <ResponsiveContainer
        width="100%"
        minWidth={1000}
        minHeight={500}
    >

        <ScatterChart
            width={600} height={400} margin={{top: 20, right: 20, bottom: 20, left: 20}}
        >
            <CartesianGrid/>
            <XAxis
                dataKey='moment'
                domain={['auto', 'auto']}
                name='Time'
                tickFormatter={(unixTime) => moment(unixTime).format('HH:mm Do')}
                type='number'
            />


            <YAxis type="number" dataKey={'value'} name='Reading'/>
            <ZAxis range={[100]}/>
            <Tooltip cursor={{strokeDasharray: '3 3'}}/>
            <Legend/>
            <Scatter name='Humidity' data={measurements.humidity} fill='#8884d8' line shape="cross"/>
            <Scatter name='Temperature' data={measurements.temperature} fill='#82ca9d' line shape="cross"/>
            <Scatter name='Carbon Monoxide' data={measurements.ppm} fill='#000000' line shape="cross"/>

        </ScatterChart>


    </ResponsiveContainer>


);

export const MyTable = ({record}) => {
    return (

        <Paper>
            {record && (
                <Test measurements={record}/>
            )}


        </Paper>

    );
}
