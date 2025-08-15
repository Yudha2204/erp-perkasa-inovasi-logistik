<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom"></div>

<div class="row">
    <div class="col-lg-4">
        <div class="form-group">
            <label class="form-label">Vendor 1</label>
                <input type="text" name="vendor[]" class="form-control mb-4" placeholder="Fill the text" id="vendor"  >
        </div>
    </div>
    <div class="col-lg-4">
        <div class="form-group">
            <label class="form-label">Total Charge/Cost 1</label>
                <input type="text" name="total_charge[]" id="total_charge" class="form-control mb-4" placeholder="Fill the text"  >
            
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-group">
            <label class="form-label">Transit Via 1</label>
                <input type="text" name="transit[]"  id="transit" class="form-control mb-4" placeholder="Fill the text"  >
        </div>
    </div>
    <div class="col-lg-1">
        <div class="form-group">
     
        </div>
    </div>
</div>

<div id="vendors"></div>
<div class="row">
    <a href="javascript:void(0)" class="btn btn-default" id="addVendor">
        <span><i class="fa fa-plus"></i></span> Add Vendor
    </a>
    <div id="vendorsAdded"></div>
</div>




<script>

$(document).ready(function () {
    $('#addVendor').on('click', function () {

    var numVendors = $('.theVendors').length;

    numVendors += 2;

    var vendors = `
        <div class="row theVendors" >
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label class="form-label form-label-nama-vendor-` + numVendors + `">Vendor ` + numVendors + `</label>
                            <input class="form-control mb-4" placeholder="Fill the text"
                                type="text" name="vendor[]">
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                                <label class="form-label form-label-total-charge-` + numVendors + `">Total Charge/Cost ` + numVendors + `</label>
                                <input class="form-control mb-4" placeholder="Fill the text"
                                    type="text" name="total_charge[]">
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                                <label class="form-label form-label-transit-via-` + numVendors + `">Transit Via ` + numVendors + `</label>
                                <input class="form-control mb-4" placeholder="Fill the text"
                                    type="text" name="transit[]">
                        </div>
                    </div>
                    <div class="col-lg-1">
                        <div class="form-group">
                            <label class="form-label form-label-delete-` + numVendors + `" style="color:white">Delete</label>
                            <a href="javascript:void(0)" class="btn text-danger remove-vendor"><span class="fe fe-trash-2 fs-14"></span></a>
                        </div>
                    </div>
                </div >`;

    $('#addVendor').before(vendors);
    });


    $(document).on('click', '.remove-vendor', function () {

    var deletedRow = $(this).closest('.theVendors');

    deletedRow.remove();

    // Mendapatkan semua baris vendor setelah yang dihapus
    var remainingRows = $('.theVendors');

    var elementsWithPrefix = [];

    remainingRows.each(function (index) {

        var numVendor = index + 2;

        labelNamaVendor = $('[class^="form-label-nama-vendor-"]');
        labelTotalCharge = $('[class^="form-label-total-charge-"]');
        labelTransitVia = $('[class^="form-label-transit-via-"]');

        inputNamaVendor = $('input[id$="[vendor_name]"');
        inputTotalChargeVendor = $('input[id$="[total_charge_vendor]"');
        inputVendorTransitVia = $('input[id$="[vendor_transit_via]"');
    });

    labelNamaVendor.each(function (index) {

        var urutan = index + 2;

        removeLabelNamaVendor = $(this).removeClass();
        addLabelNamaVendor = $(this).addClass("form-label-nama-vendor-" + urutan);
        removeTextLabelNamaVendor = $(this).html('');
        addTextLabelNamaVendor = $(this).html('Vendor ' + urutan);

    })

    labelTotalCharge.each(function (index) {

        var urutan = index + 2;

        removeLabelTotalCharge = $(this).removeClass();
        addLabelTotalCharge = $(this).addClass("form-label-total-charge-" + urutan);
        removeTextLabelTotalCharge = $(this).html('');
        addTextLabelTotalCharge = $(this).html('Total Charge/Cost ' + urutan);

    })

    labelTransitVia.each(function (index) {

        var urutan = index + 2;

        removeLabelTransitVia = $(this).removeClass();
        addLabelTransitVia = $(this).addClass("form-label-transit-via-" + urutan);
        removeTextLabelTransitVia = $(this).html('');
        addTextLabelTransitVia = $(this).html('Transit Via ' + urutan);

    })

    inputNamaVendor.each(function (index) {

        var urutan = index + 2;

        $(this).attr('id', 'the_vendors[' + urutan + '][vendor_name]');
    })

    inputTotalChargeVendor.each(function (index) {

        var urutan = index + 2;

        $(this).attr('id', 'the_vendors[' + urutan + '][total_charge_vendor]');
    })

    inputVendorTransitVia.each(function (index) {

        var urutan = index + 2;

        $(this).attr('id', 'the_vendors[' + urutan + '][vendor_transit_via]');
    })
    });
});

</script>