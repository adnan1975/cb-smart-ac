import React,{Fragment} from 'react';
import BlockButton from './BlockButton';

import {
    CardActions,
    Datagrid,
    ExportButton,
    Filter,
    List,
    RefreshButton,
    SearchInput,
    Show,
    ShowButton,
    SimpleShowLayout,
    TextField,
    EmailField
} from 'react-admin';


const AdminFilter = props => (
    <Filter {...props}>
        <SearchInput source="q" alwaysOn />

    </Filter>
);


const AdminBulkActionButtons = props => (
    <Fragment>
        <BlockButton  label="Bock/Unblock" {...props} />

    </Fragment>
);


const AdminActions = ({
                          bulkActions,
                          basePath,
                          currentSort,
                          displayedFilters,
                          exporter,
                          filters,
                          filterValues,
                          onUnselectItems,
                          resource,
                          selectedIds,
                          showFilter

                       }) => (
    <CardActions>
        {bulkActions && React.cloneElement(bulkActions, {
            basePath,
            filterValues,
            resource,
            selectedIds,
            onUnselectItems,
        })}
        {filters && React.cloneElement(filters, {
            resource,
            showFilter,
            displayedFilters,
            filterValues,
            context: 'button',
        }) }

        <RefreshButton />

    </CardActions>
);


export const AdminList = props => (
    <List {...props} actions={<AdminActions /> } bulkActionButtons={AdminBulkActionButtons}
          filters={<AdminFilter />}

    >
        <Datagrid >
            <TextField source="id" />
            <TextField source="name" />
            <TextField source="username" />
            <EmailField source="email" />
            <ShowButton />
        </Datagrid>
    </List>
);

