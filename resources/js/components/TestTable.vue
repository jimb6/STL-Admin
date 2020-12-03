<template>

    <div>
        <h3>Pagination</h3>
        <p>
            This example shows how you can use the paginator component to page data before it's bound to the datatable. The paginator can be used to page data for any child component, not just a datatable. You can even filter the paginated data. Type into the textbox below to filter the paginated data.

            <strong>Note:</strong> The styling on the paginator isn't finished yet.
        </p>
        <div class="grid-row" layout="row top-stretch">
            <div class="grid-cell" layout="row">
                <input type="text" placeholder="Type here to filter paginated data" self="size-x1" v-model="customers.filter" data-intro="You can filter the data in a datatable by typing in any of the filter fields" data-step="9" data-position="top">
            </div>
        </div>
        <div class="grid-row" layout="row top-stretch">
            <div class="grid-cell">
                <paginator :source="customers.rows" :page-size="4" :filter="customers.filter" data-intro="Use the paginator to page data for easier viewing" data-step="10" data-position="top">
                    <template scope="page">
                        <datatable id="data-table-main"
                                   :source="page.data"
                                   :filterable="false"
                                   :striped="customers.striped"
                                   :editable="customers.editable"
                                   :line-numbers="customers.lineNumbers">
                            <datatable-column v-for="column in customers.columns"
                                              :id="column.id"
                                              :label="column.label"
                                              :width="column.width"
                                              :sortable="column.sortable"
                                              :groupable="column.groupable"
                                              :formatter="column.formatter">
                            </datatable-column>
                        </datatable>
                    </template>
                </paginator>
            </div>
        </div>
    </div>

</template>
