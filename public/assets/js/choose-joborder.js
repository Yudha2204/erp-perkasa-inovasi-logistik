$(document).ready(function () {
  "use strict";

  const customer_id = $('#customer_id').val();
  const jobOrderChoosen = $('#job_order').val();
  if(customer_id) {
      getJobOrder(customer_id, true)
    }
    if(jobOrderChoosen) {
        getJobOrderDetails(jobOrderChoosen, true)
    }
  $('#customer_id').on('change', function () {
      const customer_id = $(this).val()
      getJobOrder(customer_id)
  })
  
  $('#job_order').on('change', function() {
      const jobOrderChoosen = $(this).val();
      getJobOrderDetails(jobOrderChoosen);
  })
});

function getJobOrderDetails(job_order, isEdit) {
  const splitJobOrder = job_order.split(":")
  const jobOrderId = splitJobOrder[0]
  const jobOrderSource = splitJobOrder[1]
  $.ajax({
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      type: 'GET',
      dataType: 'json',
      data: {'job_order_id': jobOrderId, 'job_order_source': jobOrderSource },
      url: jobOrderDetails,
      success:function(response) {
          $('#consignee').val(response.marketing.consignee)
          $('#shipper').val(response.marketing.shipper)
          if(response.marketing.transportation === 1) {
              $('#transportation').val("Air Freight")
          } else if(response.marketing.transportation === 2) {
              $('#transportation').val("Sea Freight")
          } else if(response.marketing.transportation === 3) {
              $('#transportation').val("Land Trucking")
          } else {
              $('#transportation').val("")
          }
          $('#commodity').val(response.marketing.description)
          
          if(response.marketing.transportation_desc) {
              $("#transportation_desc_radio").show();
              $("#transportation_desc").html(response.marketing.transportation_desc)
          }

          if(!isEdit) {
              $('#transit_via').find('option:not([disabled])').remove();
              $('#transit_via').prop('selectedIndex', 0)
          }
          if(response.vendors) {
              response.vendors.forEach(el => {
                if(!isEdit) {
                    const newOption = new Option(`${el.transit}`, `${el.id}`, false, false)
                    $('#transit_via').append(newOption)
                } else {
                    const value = $('#transit_via').val();
                    if(+value !== el.id) {
                        const newOption = new Option(`${el.transit}`, `${el.id}`, false, false)
                        $('#transit_via').append(newOption)
                    }
                }
              })
          }
      }
  })
}

function getJobOrder(customer_id, isEdit) {
    if(!isEdit) {
        $('#job_order').find('option:not([disabled])').remove();
        $('#job_order').prop('selectedIndex', 0)
    }
  $('#consignee').val('')
  $('#shipper').val('')
  $('#transportation').val("")
  $('#commodity').val('')
  $("#transportation_desc_radio").hide();

  $.ajax({
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      type: 'GET',
      dataType: 'json',
      data: {'customer_id': customer_id},
      url: jobOrder,
      success:function(response){
          response.forEach(el => {
            if(!isEdit) {
                const newOption = new Option(`${el.job_order_id} - ${el.marketing.source}`, `${el.id}:${el.marketing.source}`, false, false)
                $('#job_order').append(newOption)
            } else {
                const jobOrderChoosen = $('#job_order').val()
                if(jobOrderChoosen) {
                    const value = ($('#job_order').val()).split(":");
                    if(!(+value[0] === el.id && value[1] === el.marketing.source)) {
                        const newOption = new Option(`${el.job_order_id} - ${el.marketing.source}`, `${el.id}:${el.marketing.source}`, false, false)
                        $('#job_order').append(newOption)
                    }
                } else {
                    const newOption = new Option(`${el.job_order_id} - ${el.marketing.source}`, `${el.id}:${el.marketing.source}`, false, false)
                    $('#job_order').append(newOption)
                }
            }
          });
      }
  });
}