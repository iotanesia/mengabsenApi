/*=========================================================================================
    File Name: app-invoice-list.js
    Description: app-invoice-list Javascripts
    ----------------------------------------------------------------------------------------
    Item Name: Vuexy  - Vuejs, HTML & Laravel Admin Dashboard Template
   Version: 1.0
    Author: PIXINVENT
    Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/

$(function () {
  'use strict';

  var dtInvoiceTable = $('.invoice-list-table'),
    assetPath = '../../../app-assets/',
    invoicePreview = 'app-invoice-preview.html',
    invoiceAdd = 'app-invoice-add.html',
    invoiceEdit = 'app-invoice-edit.html';

  if ($('body').attr('data-framework') === 'laravel') {
    assetPath = $('body').attr('data-asset-path');
    invoicePreview = assetPath + 'app/invoice/preview';
    invoiceAdd = assetPath + 'app/invoice/add';
    invoiceEdit = assetPath + 'app/invoice/edit';
  }

  // Access the data attribute and get the API URL
  var apiUrl = document.getElementById('api-url').getAttribute('data-api-url');
  var download = document.getElementById('api-download').getAttribute('data-api-download');

  var modalHtml = `
    <div class="modal" id="downloadRekapModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Download Rekap</h5>
                    <button type="button" style="border:none; color:white; background-color:#ff7063; border-radius:4px;" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><b>X</b></span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Add your modal content here -->
                    From
                    <input class="form-control form-control-merge" id="startDate" type="date" name="startDate" tabindex="2" />
                    To
                    <input class="form-control form-control-merge" id="endDate" type="date" name="endDate" tabindex="2" />
                    <br>
                    <br>
                    By Username (optional)
                    <br>
                    <input class="form-control form-control-merge" id="name" type="text" name="name" placeholder="Username" tabindex="2" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="downloadButton">Download</button>
                </div>
            </div>
        </div>
    </div>
  `;

  // Append the modal HTML to the body
  $('body').append(modalHtml);

  // datatable
  if (dtInvoiceTable.length) {
    var dtInvoice = dtInvoiceTable.DataTable({
      // "processing": true,
      // "serverSide": true, 
      // "ajax": {
      //     "url": apiUrl,
      //     "type": "GET", 
      //     "beforeSend": function () {
      //       // This function will be called before the Ajax request is made. ah anjeng anjeng wkwkw
      //       // You can show a loading spinner or perform any action here. kudu manual po

      //       $('.dataTables_info').html('Showing 0 to 0 of 0 entries');
      //     },
      //     "data": function(d) {
      //       console.log('data ddddddddd', d);
      //         // Add any necessary parameters for pagination
      //         d.page = (d.start / d.length) + 1;
      //         d.per_page = d.length;

      //         // Set the 'limit' parameter based on the variable
      //         d.limit = d.length;
      //         apiFunc(d.length)
      //         // Add search parameters
      //         d.search = d.search.value; // Search keyword
      //         d.search_regex = d.search.regex; // Search regex flag
      //         return d;
      //     }
      // },
      ajax: apiUrl,
      autoWidth: false,
      columns: [
        // columns according to JSON
        { data: 'no' },
        { data: 'username' },
        { data: 'type' },
        { data: 'check_in' },
        { data: 'desc' },
        { data: 'check_out' },
        { data: 'desc_out' },
        { data: 'status' },
        { data: 'lama_bekerja' },
        { data: '' }
      ],
      columnDefs: [
        {
          // For Responsive
          className: 'control',
          responsivePriority: 2,
          targets: 0
        },
        {
          // Status
          targets: 7,
          render: function (data, type, full, meta) {
            var $status = full['status'];
            if ($status === 'On Time') {
              return '<span style="color:#4bd909; font-weight:600;">' + $status + '</span>';
            } else if ($status === '-') {
              return $status;
            } else {
              return '<span style="color:red; font-weight:600;">' + $status + '</span>';
            }
          }
        },
        {
          // Actions
          targets: -1,
          title: 'Actions',
          width: '80px',
          orderable: false,
          render: function (data, type, full, meta) {
            return (
              '<div class="d-flex align-items-center col-actions">' +
              '<a class="mr-1" href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="Send Mail">' +
              feather.icons['send'].toSvg({ class: 'font-medium-2' }) +
              '</a>' +
              '<a class="mr-1" href="' +
              invoicePreview +
              '" data-toggle="tooltip" data-placement="top" title="Preview Invoice">' +
              feather.icons['eye'].toSvg({ class: 'font-medium-2' }) +
              '</a>' +
              '<div class="dropdown">' +
              '<a class="btn btn-sm btn-icon px-0" data-toggle="dropdown">' +
              feather.icons['more-vertical'].toSvg({ class: 'font-medium-2' }) +
              '</a>' +
              '<div class="dropdown-menu dropdown-menu-right">' +
              '<a href="javascript:void(0);" class="dropdown-item">' +
              feather.icons['download'].toSvg({ class: 'font-small-4 mr-50' }) +
              'Download</a>' +
              '<a href="' +
              invoiceEdit +
              '" class="dropdown-item">' +
              feather.icons['edit'].toSvg({ class: 'font-small-4 mr-50' }) +
              'Edit</a>' +
              '<a href="javascript:void(0);" class="dropdown-item">' +
              feather.icons['trash'].toSvg({ class: 'font-small-4 mr-50' }) +
              'Delete</a>' +
              '<a href="javascript:void(0);" class="dropdown-item">' +
              feather.icons['copy'].toSvg({ class: 'font-small-4 mr-50' }) +
              'Duplicate</a>' +
              '</div>' +
              '</div>' +
              '</div>'
            );
          }
        }
      ],
      dom:
        '<"row d-flex justify-content-between align-items-center m-1"' +
        '<"col-lg-6 d-flex align-items-center"l<"dt-action-buttons text-xl-right text-lg-left text-lg-right text-left "B>>' +
        '<"col-lg-6 d-flex align-items-center justify-content-lg-end flex-lg-nowrap flex-wrap pr-lg-1 p-0"f<"invoice_status ml-sm-2">>' +
        '>t' +
        '<"d-flex justify-content-between mx-2 row"' +
        '<"col-sm-12 col-md-6"i>' +
        '<"col-sm-12 col-md-6"p>' +
        '>',
      language: {
        sLengthMenu: 'Show _MENU_',
        search: 'Search',
        searchPlaceholder: 'Search....',
        paginate: {
          // remove previous & next text from pagination
          previous: '&nbsp;',
          next: '&nbsp;'
        }
      },
      // Buttons with Modal
      buttons: [
        {
          text: 'Download Rekap',
          className: 'btn btn-primary btn-add-record ml-2',
          action: function (e, dt, button, config) {
            // window.location = invoiceAdd;
            $('#downloadRekapModal').modal('show');
          }
        }
      ],
      initComplete: function () {
        $(document).find('[data-toggle="tooltip"]').tooltip();
        // Adding role filter once table initialized
        this.api()
          .columns(7)
          .every(function () {
            var column = this;
            var select = $(
              '<select id="UserRole" class="form-control ml-50 text-capitalize"><option value=""> Select Status </option></select>'
            )
              .appendTo('.invoice_status')
              .on('change', function () {
                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                column.search(val ? '^' + val + '$' : '', true, false).draw();
              });

            column
              .data()
              .unique()
              .sort()
              .each(function (d, j) {
                select.append('<option value="' + d + '" class="text-capitalize">' + d + '</option>');
              });
          });
      },
      drawCallback: function (response) {
        $(document).find('[data-toggle="tooltip"]').tooltip();
      }
    });
  }
  
  document.getElementById('downloadButton').addEventListener('click', function() {
      var fromDate = document.getElementById('startDate').value;
      var toDate = document.getElementById('endDate').value;
      var name = document.getElementById('name').value;
  
      // Assuming you want to pass the date and username as parameters to the download URL
      var downloadUrl = download + '?startDate=' + fromDate + '&endDate=' + toDate + '&name=' + name;
  
      window.open(downloadUrl, '_blank');
  });
  
});
