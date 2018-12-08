import React from 'react';
import List from '@material-ui/core/List';
import ListItem from '@material-ui/core/ListItem';

import {MyTable} from './table';

export const FolderList = ({ record }) => {
    return (

        <List >
            <ListItem>
                <MyTable record={record}/>
            </ListItem>

        </List>

    );
}
