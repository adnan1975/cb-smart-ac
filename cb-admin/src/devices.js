import React from 'react';
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
    TextField
} from 'react-admin';
import {FolderList} from './folderlists';


const DeviceFilter = props => (
    <Filter {...props}>
        <SearchInput source="q" alwaysOn />

    </Filter>
);



const DeviceActions = ({
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
        <ExportButton
            resource={resource}
            sort={currentSort}
            filter={filterValues}
            exporter={exporter}
        />
        <RefreshButton />

    </CardActions>
);



export const DeviceList = props => (
    <List {...props} actions={<DeviceActions />}
          filters={<DeviceFilter />}

    >
        <Datagrid >
            <TextField source="id" />
            <TextField source="serialNumber" />
            <TextField source="macAddress" />
            <TextField source="year" />
            <TextField source="isActive" />
            <TextField source="owner" />
            <ShowButton />
        </Datagrid>
    </List>
);

const Aside = ( {record} ) => (

       <FolderList  record={record} />


);


export const DeviceShow = (props) => (
    <Show aside={<Aside />} {...props}>
        <SimpleShowLayout>
            <TextField source="id" />
            <TextField source="serialNumber" />
            <TextField source="macAddress" />
            <TextField source="year" />
            <TextField source="isActive" />
            <TextField source="owner" />
        </SimpleShowLayout>
    </Show>
);

