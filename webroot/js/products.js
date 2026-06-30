"use strict";

// HOST_URL must be defined globally in the HTML template before this script.
// e.g., <script>var HOST_URL = "<?= $this->Url->build(['controller' => 'Products', 'action' => 'search']); ?>";</script>

var ProductsDataTable = function () {
    var table; // Reference to the DataTable instance

    var initTable = function () {
        table = $('#kt_datatable_products').KTDatatable({
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: HOST_URL,
                        method: 'POST',
                        map: function (raw) {
                            var dataSet = raw;
                            if (typeof raw.data !== 'undefined') {
                                dataSet = raw.data;
                            }
                            return dataSet;
                        },
                        params: {
                            query: {
                                Category: function () {
                                    return $('#kt_datatable_search_category_products').val();
                                },
                                Status: function () {
                                    return $('#kt_datatable_search_status_products').val();
                                }
                            }
                        }
                    }
                },
                pageSize: 10,
                serverPaging: true,
                serverFiltering: true,
                serverSorting: true
            },
            layout: {
                scroll: true,
                footer: false
            },
            sortable: true,
            pagination: true,
            search: {
                input: $('#kt_datatable_search_query_products')
            },
            columns: [
                {
                    field: 'display_name',
                    title: 'Produit',
                    width: 250,
                    template: function (row) {
                        return '<div class="d-flex align-items-center">\
                                    <div class="symbol symbol-40 symbol-sm flex-shrink-0">\
                                        <img src="' + row.display_name.image + '" alt="photo">\
                                    </div>\
                                    <div class="ml-3">\
                                        <span class="text-dark-75 font-weight-bold line-height-sm d-block pb-2">' + (row.display_name.reference ? row.display_name.reference + ' - ' : '') + row.display_name.title + '</span>\
                                        <span class="text-muted font-size-sm">' + (row.display_name.unite ? row.display_name.unite : '') + '</span>\
                                    </div>\
                                </div>';
                    }
                },
                {
                    field: 'category_title',
                    title: 'Catégorie',
                    width: 120
                },
                {
                    field: 'quantity',
                    title: 'Stock',
                    width: 130,
                    template: function (row) {
                        return '<span class="text-dark-75 font-weight-bold" data-toggle="tooltip" data-theme="dark" title="' + (row.stock_tooltip ? row.stock_tooltip : '') + '">' + row.quantity + '</span>';
                    }
                }, {
                    field: 'status',
                    title: 'Statut',
                    width: 80,
                    autoHide: false,
                    // callback function support for column rendering
                    template: function (row) {
                        var status = {
                            0: {
                                'title': 'Innactif',
                                'class': ' label-light-danger'
                            },
                            1: {
                                'title': 'Actif',
                                'class': ' label-light-success'
                            },
                        };
                        return '<span class="label font-weight-bold label-lg ' + status[row.status].class + ' label-inline">' + status[row.status].title + '</span>';
                    },
                },
                {
                    field: 'actions',
                    title: 'Actions',
                    sortable: false,
                    width: 120,
                    overflow: 'visible',
                    autoHide: false,
                    template: function (row) {
                        return row.actions;
                    }
                }
            ]
        });
        // Initialize tooltips on layout updates
        table.on('kt-datatable--on-layout-updated', function (e) {
            $('[data-toggle="tooltip"]').tooltip();
        });

        // Custom filter dropdowns
        $('#kt_datatable_search_category_products, #kt_datatable_search_status_products').on('change', function () {
            table.reload();
        });
    };

    return {
        init: function () {
            initTable();
        }
    };
}();

jQuery(document).ready(function () {
    ProductsDataTable.init();
});
