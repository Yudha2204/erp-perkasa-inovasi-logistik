@extends('layouts.app')
@push('styles')
    <style>
        .form-control:focus {
            box-shadow: none;
            border-color: #d1d3e2;
        }

        .btn:focus {
            box-shadow: none !important;
            background-color: unset;
        }
    </style>
@endpush
@section('content')

    <div class="main-content app-content mt-0">
        <div class="side-app">

            <!-- CONTAINER -->
            <div class="main-container container-fluid">

                <!-- PAGE-HEADER -->
                <div class="page-header mb-0">
                    <h1>Marketing</h1>
                </div>
                <h4>Edit Quotation</h4>
                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true">×</button>
                        <strong>Whoops!</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <!-- PAGE-HEADER END -->
                <!-- ROW-1 -->
                <form action="{{ route('marketing.export.update-quotation') }}" method="post">
                    @csrf
                <div class="row">
                        <input type="hidden" name="quotation_id" value="{{ $quotation->id }}">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Bill To</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">Customer</label>
                                                <div class="d-flex d-inline">
                                                    <select class="form-control select2 form-select"
                                                        data-placeholder="Choose one" name="customer_id" id="customer_id"
                                                        >
                                                        <option label="Choose one" selected disabled></option>
                                                        @foreach ($contact as $c)
                                                            <option value="{{ $c->id }}"
                                                                {{ $c->id == $marketingExport->contact_id ? 'selected' : '' }}>
                                                                {{ $c->customer_name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div id="btn_edit_contact"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">Phone Number</label>
                                                <input class="form-control mb-4" id="phone_number" type="number" disabled
                                                    value="{{ $marketingExport->contact->phone_number }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">Company Address</label>
                                                <input class="form-control mb-4" id="company_address" type="text"
                                                    disabled value="{{ $marketingExport->contact->address }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">Company Name</label>
                                                <input class="form-control mb-4" id="company_name" type="text" disabled
                                                    value="{{ $marketingExport->contact->company_name }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">Payment Term</label>
                                                <div class="term-payment-class">
                                                    @foreach ($marketingExport->contact->termPaymentContacts as $index => $data)
                                                        @if($index === 0)
                                                        <input class="form-control mb-4" id="payment_term[{{$index}}]" value="{{ $data->term_payment->name }}" readonly type="text">
                                                        @else
                                                        <input class="form-control mb-4 another-term-payment" id="payment_term[{{$index}}]" value="{{ $data->term_payment->name }}" readonly type="text">
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">Customer ID</label>
                                                <input class="form-control mb-4" id="get_customer_id" type="text"
                                                    disabled value="{{ $marketingExport->contact->customer_id }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Origin</label>
                                                <input class="form-control mb-4" id="origin" type="text" disabled
                                                    value="{{ $marketingExport->origin }}">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">Destination</label>
                                                <input class="form-control mb-4" id="destination" type="text" disabled
                                                    value="{{ $marketingExport->destination }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Quotation</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">Date</label>
                                                <input class="form-control mb-4" name="date" placeholder="Fill the text"
                                                    type="date" value="{{ $quotation->date }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">Quotation No</label>
                                                <input class="form-control mb-4" name="quotation_no"
                                                    placeholder="Fill the text" type="text"
                                                    value="{{ $quotation->quotation_no }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">Valid Until</label>
                                                <input class="form-control mb-4" name="valid_until"
                                                    placeholder="Fill the text" type="date"
                                                    value="{{ $quotation->valid_until }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">Shipper</label>
                                                <input class="form-control mb-4" id="shipper" type="text" disabled
                                                    value="{{ $marketingExport->shipper }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">Consignee</label>
                                                <input class="form-control mb-4" id="consignee" type="text" disabled
                                                    value="{{ $marketingExport->consignee }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Project Desc</label>
                                                <textarea class="form-control mb-4" name="project_desc" placeholder="Fill the text" rows="4">{{ $quotation->project_desc }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="form-label">Total Weight</label>
                                                <input class="form-control mb-4" name="total_weight" type="text"
                                                    disabled value="{{ $marketingExport->total_weight }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="form-label">Total Volume</label>
                                                <input class="form-control mb-4" name="total_volume" type="text"
                                                    disabled value="{{ $marketingExport->total_volume }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="form-label" for="currency">Currency</label>
                                                <select class="form-control select2 form-select"
                                                    data-placeholder="Choose one" name="currency" id="currency">
                                                    <option label="Choose one" selected disabled></option>
                                                    @foreach ($currencies as $c)
                                                        <option value="{{ $c->initial }}"
                                                            {{ $c->id == $quotation->currency_id ? 'selected' : '' }}>
                                                            {{ $c->initial }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!--row 4-->
                        </div>
                </div>
                <!-- ROW-1 END -->
                <!-- row 2 -->
                <div class="row">

                    <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>DESCRIPTION</th>
                                                        <th>TOTAL</th>
                                                        <th>REMARK</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="table-body">
                                                    <!-- Group forms will be added dynamically here -->
                                                </tbody>
                                            </table>

                                            <a href="javascript:void(0)" class="btn border btn-block"
                                                style="background-color: #DADADA" id="add-group">
                                                <i class="fa fa-plus"></i> Add new group
                                            </a>

                                            <div class="row justify-content-end">
                                                <div class="col-lg-6">
                                                    <table width="50%" class="table table-bordered mt-5">
                                                        <tr>
                                                            <td colspan="4">
                                                                <div class="d-flex justify-content-between">
                                                                    <div>
                                                                        <strong>TOTAL</strong>
                                                                    </div>
                                                                    <div>
                                                                        <strong id="overall-total-currency">{{ $quotation->currency->initial }}</strong>
                                                                        <input type="text" id="overall-total"
                                                                            name="total_all" readonly
                                                                            style="border: 0px; text-align: right">
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    {{-- <div class="form-group">
                                                        <div class="btn-list text-end">
                                                            <button type="submit" class="btn btn-primary">Update</button>
                                                        </div>
                                                    </div> --}}
                                                    <div class="form-group">
                                                        <div class="btn-list text-end">
                                                            <a href="{{ route('marketing.export.index') }}" class="btn btn-default">Cancel</a>
                                                            <button type="submit" class="btn btn-primary">Update</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br><br><br><br>
                </form>
                <!--row 4-->
            </div>
            <!-- CONTAINER END -->
        </div>
    </div>

@endsection
@push('scripts')
    <!-- SELECT2 JS -->
    <script src="{{ url('assets/plugins/select2/select2.full.min.js') }}"></script>
    <script src="{{ url('assets/js/select2.js') }}"></script>

    <!-- MULTI SELECT JS-->
    <script src="{{ url('assets/plugins/multipleselect/multiple-select.js') }}"></script>
    <script src="{{ url('assets/plugins/multipleselect/multi-select.js') }}"></script>

    <script>
        $(document).ready(function() {

            //show edit btn customer when customer id was selected
            $('select[name="customer_id"]').change(function() {
                var id = this.value;

                $("#btn_edit_contact").html("");
                var editContactBtn = $(
                    `<a href="{{ url('master-data/contact/`+ id + `/edit') }}" target="_blank" id="btn-edit-contact" class="btn text-primary btn-sm mt-2"
        data-bs-toggle="tooltip" data-bs-original-title="Edit data customer"><span class="fe fe-edit fs-14"></span></a>`
                );
                editContactBtn.appendTo('#btn_edit_contact');
            });

            //fetch data customer
            $('#customer_id').on('change', function() {
                var id = $(this).val();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        'id': id
                    },
                    url: '{{ route('marketing.export.getDataCustomer') }}',
                    success: function(response) {
                        $('#phone_number').val(response.data.phone_number);
                        $('#company_address').val(response.data.address);
                        $('#company_name').val(response.data.company_name);
                        $('.term-payment-class .another-term-payment').remove()
                        response.data?.term_payment_contacts.forEach((el, idx) => {
                            const element = `#payment_term\\[${idx}\\]`
                            if(idx === 0) {
                                $(element).val(el.term_payment.name);
                            } else {
                                const newInput = document.createElement('input');
                                newInput.className = 'form-control mb-4 another-term-payment';
                                newInput.id = `payment_term[${idx}]`;
                                newInput.type = 'text';
                                newInput.disabled = true;
                                newInput.value = el.term_payment.name;

                                document.querySelector('.term-payment-class').append(newInput)
                            }
                        })
                        $('#get_customer_id').val(response.data.customer_id);
                    }
                });
            });

            //Customisasi group here
            let groupCount = 0;
            let json_group = JSON.parse(`<?php echo $json_group; ?>`);

            // initialize group
            function initializeGroup(group, group_index) {
                if (groupCount < 26) { // Check if groupCount is less than 26 (A to Z)
                    let groupChar = String.fromCharCode(65 + groupCount); // ASCII code for A is 65
                    let newRow = ``;
                    const currency = '{{ $quotation->currency->initial }}';

                    group.items.forEach((item, item_index) => {
                        let numericValue = item.total.replace(/,/g, ''); // Remove existing commas
                        let formattedValue = numericValue.replace(/\B(?=(\d{3})+(?!\d))/g, ',');

                        newRow += `
                        <tr class="group-form" data-group="${groupChar}">
                            <input type="hidden" name="alphabet" value="${groupChar}">
                            <td width="50%">
                                <input type="text" class="form-control description-input" name="description_${groupChar}[]" value="${item.description}"  />
                                <input type="hidden" class="form-control description-input" name="group_count" value="${group_index}" />
                            </td>
                            <td width="20%">
                                <div class="d-flex align-items-center justify-content-between"">
                                    <div class="currency-text">${currency}</div>
                                    <input type="text" style="text-align: right; margin-left: 5px" class="form-control border-left-0 pl-2 text-right total-input" name="total_${groupChar}[]" data-group="${groupChar}" autocomplete="off" value="${formattedValue}" />
                                </div>
                            </td>
                            <td width="20%">
                                <input type="text" class="form-control remark-input" name="remark_${groupChar}[]" value="${item.remark ?? '-'}" />
                            </td>
                            <td width="10%">
                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn delete-row"><i class="fa fa-trash text-danger"></i></button>
                                    <button type="button" class="btn add-row"><i class="fa fa-plus text-primary"></i></button>
                                </div>
                            </td>
                        </tr>
                    `;
                    });

                    newRow += `
                        <tr class="group-total" data-group="${groupChar}" style="background-color: #C0E3FF;">
                            <td>
                                <div class="text-white bg-primary p-2 group-text" style="border-radius: 5px;">Total ${groupChar}</div>
                            </td>
                            <td>
                                <div class="text-white bg-primary p-2" style="border-radius: 5px;">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="currency-text">${currency}</div>
                                        <div class="group-total-value">0</div>
                                    </div>
                                </div>
                            </td>
                            <td></td>
                            <td>
                                <button type="button" class="btn remove-group"><i class="fa fa-trash text-danger"></i></button>
                            </td>
                        </tr>
                    `;

                    groupCount++
                    $('#table-body').append(newRow);

                    return groupChar
                } else {
                    alert("You have reached the maximum limit of groups (A to Z).");
                }
            }

            // Function to add a new group
            function addGroup() {
                if (groupCount < 26) { // Check if groupCount is less than 26 (A to Z)
                    let groupChar = String.fromCharCode(65 + groupCount); // ASCII code for A is 65
                    let newRow = `
                        <tr class="group-form" data-group="${groupChar}">
                            <input type="hidden" name="alphabet" value="${groupChar}">
                            <td width="50%">
                                <input type="text" class="form-control description-input" name="description_${groupChar}[]" />
                                <input type="hidden" class="form-control description-input" name="group_count" value="${groupCount}" />
                            </td>
                            <td width="20%">
                                <div class="d-flex align-items-center justify-content-between"">
                                    <div class="currency-text">IDR</div>
                                    <input type="text" style="text-align: right; margin-left: 5px" class="form-control border-left-0 pl-2 text-right total-input" name="total_${groupChar}[]" data-group="${groupChar}" autocomplete="off" />
                                </div>
                            </td>
                            <td width="20%">
                                <input type="text" class="form-control remark-input" name="remark_${groupChar}[]" />
                            </td>
                            <td width="10%">
                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn delete-row"><i class="fa fa-trash text-danger"></i></button>
                                    <button type="button" class="btn add-row"><i class="fa fa-plus text-primary"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr class="group-total" data-group="${groupChar}" style="background-color: #C0E3FF;">
                            <td>
                                <div class="text-white bg-primary p-2 group-text" style="border-radius: 5px;">Total ${groupChar}</div>
                            </td>
                            <td>
                                <div class="text-white bg-primary p-2" style="border-radius: 5px;">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="currency-text">IDR</div>
                                        <div class="group-total-value">0</div>
                                    </div>
                                </div>
                            </td>
                            <td></td>
                            <td>
                                <button type="button" class="btn remove-group"><i class="fa fa-trash text-danger"></i></button>
                            </td>
                        </tr>
                    `;

                    $('#table-body').append(newRow);
                    groupCount++;
                } else {
                    alert("You have reached the maximum limit of groups (A to Z).");
                }
            }

            // Function to update the total for a group
            function updateGroupTotal(groupChar) {
                let total = 0;
                $(`tr[data-group=${groupChar}]`).each(function() {
                    let rowTotal = $(this).find('.total-input').val();

                    // Check if rowTotal is not empty, contains only digits and commas, and is not zero
                    if (/^\d+([\,]\d+)*$/.test(rowTotal) && parseFloat(rowTotal.replace(/,/g, '')) !== 0) {
                        let numericValue = rowTotal.replace(/,/g, ''); // Remove commas for calculation
                        total += parseInt(numericValue) ||
                            0; // Use parseInt to treat the value as an integer
                    }
                });
                let formattedTotal = total > 0 ? total.toLocaleString() :
                    '0'; // Format the total with commas or display "0"
                $(`tr.group-total[data-group=${groupChar}]`).find('.group-total-value').text(formattedTotal);
                updateOverallTotal();
            }

            // Function to update the overall total
            function updateOverallTotal() {
                let overallTotal = 0;
                $('.group-total-value').each(function() {
                    let groupTotal = $(this).text();

                    // Check if groupTotal is not empty, contains only digits and commas, and is not zero
                    if (/^\d+([\,]\d+)*$/.test(groupTotal) && parseFloat(groupTotal.replace(/,/g, '')) !==
                        0) {
                        let numericValue = groupTotal.replace(/,/g, ''); // Remove commas for calculation
                        overallTotal += parseInt(numericValue) ||
                            0; // Use parseInt to treat the value as an integer
                    }
                });
                let formattedOverallTotal = overallTotal > 0 ? overallTotal.toLocaleString() :
                    '0'; // Format the overall total with commas or display "0"
                // $('#overall-total').text(` ${formattedOverallTotal}`);
                $('#overall-total').val(` ${formattedOverallTotal}`);
            }

            // Event handler to add a new group
            $('#add-group').click(function() {
                addGroup();
            });

            // Event handler to add a new entry row
            $('#table-body').on('click', '.add-row', function() {
                let groupChar = $(this).closest('tr.group-form').data('group');
                let newRow = addGroupRow(groupChar); // Store the new row in a variable
                $(this).closest('tr.group-form').after(newRow); // Add the new row after the current group
            });

            // Function to add a new row within a group
            function addGroupRow(groupChar) {
                let newRow = `
        <tr class="group-form" data-group="${groupChar}">
            <td width="50%">
                <input type="text" class="form-control description-input" name="description_${groupChar}[]" />
                <input type="hidden" class="form-control description-input" name="group_count" value="${groupCount}" />
            </td>
            <td width="20%">
                <div class="d-flex align-items-center justify-content-between"">
                    <div class="currency-text">IDR</div>
                    <input type="text" style="text-align: right; margin-left: 5px" class="form-control border-left-0 pl-2 text-right total-input" name="total_${groupChar}[]" data-group="${groupChar}" autocomplete="off" />
                </div>
            </td>
            <td width="20%">
                <input type="text" class="form-control remark-input" name="remark_${groupChar}[]" />
            </td>
            <td width="10%">
                <div class="d-flex justify-content-between">
                    <button type="button" class="btn delete-row"><i class="fa fa-trash text-danger"></i></button>
                    <button type="button" class="btn add-row"><i class="fa fa-plus text-primary"></i></button>
                </div>
            </td>
        </tr>
        `;

                return newRow;
            }

            // Event handler to remove a row
            $('#table-body').on('click', '.delete-row', function() {
                let groupChar = $(this).closest('tr.group-form').data('group');

                // Check if there is more than one row in the group
                if ($(`tr.group-form[data-group=${groupChar}]`).length > 1) {
                    $(this).closest('tr.group-form').remove();
                    updateGroupTotal(groupChar);
                } else {
                    alert("Cannot delete the last row in a group.");
                }
            });

            // Event handler to remove a group
            $('#table-body').on('click', '.remove-group', function() {
                if (confirm('Are you sure you want to remove this group?')) {
                    let groupChar = $(this).closest('tr.group-total').data('group');

                    $(`tr[data-group=${groupChar}]`).remove();
                    groupCount--;

                    reassignGroupNames();
                    updateOverallTotal();
                }
            });

            // Function to reassign group names
            function reassignGroupNames() {
                let groupChar = 'A';
                $('#table-body').find('.group-total').each(function() {
                    let currentGroupChar = $(this).data('group');
                    $(this).data('group', groupChar);
                    $(this).find('.group-text').text(`Total ${groupChar}`);
                    $(`tr[data-group=${currentGroupChar}]`).attr('data-group', groupChar).find('input')
                        .each(function() {
                            let name = $(this).attr('name');
                            $(this).attr('name', name.replace(currentGroupChar, groupChar));
                            $(this).data('group', groupChar);
                        });
                    groupChar = String.fromCharCode(groupChar.charCodeAt(0) + 1);
                });
            }

            // Event handler to update total on total input change
            $('#table-body').on('input', '.total-input', function() {
                let groupChar = $(this).data('group');
                console.log(groupChar)
                let inputValue = $(this).val();

                // Check if inputValue is not empty and contains only digits and commas
                if (/^\d+([\,]\d+)*$/.test(inputValue)) {
                    let numericValue = inputValue.replace(/,/g, ''); // Remove existing commas
                    let formattedValue = numericValue.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                    $(this).val(formattedValue);
                    updateGroupTotal(groupChar);
                } else {
                    // Handle non-numeric input here (optional)
                    updateGroupTotal(groupChar); // Recalculate even if the input is empty or non-numeric
                }
            });

            // Event handler to update currency
            $('#currency').on('change', function() {
                console.log('asdasdas');
                if ($(this).val() == "") {
                    $('.currency-text').text('IDR')
                    $('#overall-total-currency').text('IDR')
                } else {
                    $('.currency-text').text($(this).val())
                    $('#overall-total-currency').text($(this).val())
                }
            })

            // initialize group
            json_group.forEach((group, group_index) => {
                groupChar = initializeGroup(group, group_index);
                updateGroupTotal(groupChar)
            })
        });
    </script>
@endpush
