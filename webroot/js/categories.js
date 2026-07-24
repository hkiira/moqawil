"use strict";

var KTDatatableRemoteAjaxDemo = function () {

    var formatChildCategories = function(data) {
        if (!data || data.length === 0) {
            return '<div class="p-4 text-muted text-center font-weight-bold">Aucune sous-catégorie rattachée à cette famille</div>';
        }

        var html = '<div class="card card-custom p-4 my-2" style="background-color: #fcfcfd; border: 1px dashed #3699ff;">';
        html += '<h6 class="font-weight-bolder text-primary mb-3"><i class="flaticon2-tag text-primary mr-2"></i>Sous-Familles & Catégories de cette famille</h6>';
        html += '<table class="table table-bordered table-head-custom mb-0">';
        html += '<thead><tr><th>Nom</th><th>Statut</th><th class="text-right">Actions</th></tr></thead>';
        html += '<tbody>';

        $.each(data, function(index, cat) {
            var statusHtml = '';
            if (cat.Status == 1) {
                statusHtml = '<span class="label label-success label-dot mr-2"></span><span class="font-weight-bold text-success">Actif</span>';
            } else {
                statusHtml = '<span class="label label-danger label-dot mr-2"></span><span class="font-weight-bold text-danger">Inactif</span>';
            }

            html += '<tr>';
            html += '<td><span class="font-weight-bolder text-dark">' + cat.Title + '</span><br><span class="text-muted font-size-sm">' + cat.Code + '</span></td>';
            html += '<td>' + statusHtml + '</td>';
            html += '<td class="text-right">' + cat.Actions + '</td>';
            html += '</tr>';
        });

        html += '</tbody></table></div>';
        return html;
    };

    var initTable = function () {
        var tableEl = $('#kt_datatable');

        var table = tableEl.DataTable({
            language: {
                sEmptyTable:     "Aucune catégorie disponible",
                sInfo:           "Affichage des catégories _START_ à _END_ sur _TOTAL_",
                sInfoEmpty:      "Affichage des catégories 0 à 0 sur 0",
                sInfoFiltered:   "(filtré à partir de _MAX_ catégories au total)",
                sInfoPostFix:    "",
                sInfoThousands:  ",",
                sLengthMenu:     "Afficher _MENU_ catégories",
                sLoadingRecords: "Chargement...",
                sProcessing:     "Traitement...",
                sSearch:         "Rechercher :",
                sZeroRecords:    "Aucune catégorie correspondante trouvée",
                oPaginate: {
                    sFirst:    "Premier",
                    sLast:     "Dernier",
                    sNext:     "Suivant",
                    sPrevious: "Précédent"
                },
            },
            responsive: false,
            searchDelay: 500,
            pageLength: 50,
            processing: true,
            serverSide: true,
            ajax: {
                url: HOST_URL,
                type: 'GET',
            },
            columns: [
                {
                    className: 'details-control',
                    orderable: false,
                    data: null,
                    render: function(data, type, full, meta) {
                        if (full.parentcategory == "aucune") {
                            return '<i class="fa fa-chevron-right text-muted toggle-details" style="font-size: 0.9rem; cursor: pointer;"></i>';
                        }
                        return '';
                    },
                    width: '30px'
                },
                {
                    data: 'name',
                    render: function(data, type, full, meta) {
                        return '<div class="d-flex align-items-center">' +
                            '<div class="symbol symbol-40 flex-shrink-0">' +
                                '<div class="symbol-label" style="background-image:url(' + full.img + ')"></div>' +
                            '</div>' +
                            '<div class="ml-2">' +
                                '<div class="text-dark-75 font-weight-bold line-height-sm">' + full.name + '</div>' +
                                '<span class="font-size-sm text-muted">' + full.code + '</span>' +
                            '</div>' +
                        '</div>';
                    }
                },
                { data: 'category' },
                {
                    data: 'status',
                    width: '75px',
                    render: function(data, type, full, meta) {
                        var status = {
                            1: { title: 'Actif',   state: 'success' },
                            0: { title: 'Inactif', state: 'danger'  },
                        };
                        var st = status[data] || status[0];
                        return '<span class="label label-' + st.state + ' label-dot mr-2"></span>' +
                               '<span class="font-weight-bold text-' + st.state + '">' + st.title + '</span>';
                    }
                },
                { data: 'actions', orderable: false, responsivePriority: -1 },
            ],
        });

        // Expand / collapse child categories
        tableEl.find('tbody').on('click', 'td.details-control', function () {
            var tr   = $(this).closest('tr');
            var row  = table.row(tr);
            var icon = $(this).find('i.toggle-details');

            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
                icon.removeClass('fa-chevron-down').addClass('fa-chevron-right');
            } else {
                var parentId = row.data().id;
                icon.removeClass('fa-chevron-right').addClass('fa-spinner fa-spin');

                var url = HOST_URL;
                if (url.indexOf('/search') !== -1) {
                    url = url.split('/search')[0] + '/child-categories/' + parentId;
                } else {
                    url = url + '/child-categories/' + parentId;
                }

                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        icon.removeClass('fa-spinner fa-spin').addClass('fa-chevron-down');
                        row.child(formatChildCategories(data)).show();
                        tr.addClass('shown');
                    },
                    error: function() {
                        icon.removeClass('fa-spinner fa-spin').addClass('fa-chevron-right');
                        toastr.error('Erreur lors du chargement des sous-catégories.');
                    }
                });
            }
        });
    };

    return {
        init: function () {
            initTable();
        },
    };
}();

jQuery(document).ready(function () {
    KTDatatableRemoteAjaxDemo.init();
});

