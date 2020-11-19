

var StatusWidget = {

  // status_url: "/doctool/build_status",
  status_url: "/build_status",

  trigger_build_url: "/trigger_build",

  statusTimeout: null,

  // blurProductQuantity: function (event) {
  //   this.changeProductQuantity(0, event.currentTarget.id);
  //   this.updateWidget();
  // },
  //
  // cancelCheckout: function() {
  //    this.downloadingCancelled = true;
  //    // this.setDownloadButton();
  //    $(this.element).find('.osf_widget_error').html("<span>There was an error starting the purchase.</span>");
  // },

  // startPayInvoice: function() {
  //   // Hide the pay by card button
  //   var info = this.getPurchaseDetailsAndUpdateDisplay();
  //
  //   $.ajax({
  //     url: this.invoice_url,
  //     context: this,
  //     cache: false,
  //     success: $.proxy(this, 'successResponsePurchaseOrder'),
  //     error: $.proxy(this, 'errorResponsePurchaseOrder'),
  //     data: {
  //       purchase_currency:    info.currency,
  //       purchase_price_cents: info.total_price,
  //       sku_purchase_param_array: info.quantities,
  //       email_address: info.email,
  //       name: info.name,
  //       company_name: info.company_name
  //     },
  //     method: 'POST',
  //     // dataType: 'json',
  //     timeout: 10 * 1000
  //   });
  //
  //   this.errorTimeout = setTimeout($.proxy(this, 'cancelCheckout'), 50 * 1000);
  // },

  statusSuccess: function (data, textStatus, jqXHR) {
    $(this.element)
       .find('.doctool_status')
       .html(data.html_system_status);
    this.statusTimeout = setTimeout($.proxy(this, 'statusUpdate'), 500);
  },

  triggerBuildSuccess: function (data, textStatus, jqXHR) {
    console.log('Trigger build success.');
  },

  statusError:  function (data, textStatus, jqXHR) {
    debugger;
  },

  // checkoutBorked: function() {
  //    console.error("Well, that's annoying.");
  //    // Also the stripe docs don't say what to do in this case.
  //    debugger;
  // },
  //
  // errorResponse: function(xhr, status, error) {
  //   var err = eval("(" + xhr.responseText + ")");
  //   // alert(err.Message);
  //   debugger;
  // },

  statusUpdate: function() {
       $.ajax({
         url: this.status_url,
         context: this,
         cache: false,
         success: $.proxy(this, 'statusSuccess'),
    //     error: $.proxy(this, 'errorResponsePurchaseOrder'),
         method: 'GET',
         timeout: 2 * 1000
    });
  },

  triggerBuild: function() {
    $.ajax({
      url: this.trigger_build_url,
      context: this,
      cache: false,
      success: $.proxy(this, 'triggerBuildSuccess'),
      //     error: $.proxy(this, 'errorResponsePurchaseOrder'),
      method: 'GET',
      timeout: 2 * 1000
    });
  },


  _create: function() {
  },

  _init: function() {

    // this.statusTimeout = setTimeout($.proxy(this, 'statusUpdate'), 250);

    $(this.element)
      .find('.doctool_trigger_rebuild')
      .click($.proxy(this, 'triggerBuild'));

    // this.checkout_url = $(this.element).data('checkout_url');
    // if (!this.checkout_url) {
    //     console.error('checkout_url is borked');
    //     return;
    // }

    // this.invoice_url = $(this.element).data('invoice_url');
    // if (!this.invoice_url) {
    //   console.error('invoice_url is borked');
    //   return;
    // }
    //
    // $('.osf_sku_quantity_input').on('input', $.proxy(this, 'blurProductQuantity'));

    // $('.osf_sku_quantity_inc').click($.proxy(this, 'clickIncrementProductQuantity'));
    //
    // $('#osf_currency_selector').change($.proxy(this, 'currencyChange'));
    // this.updateCurrencyVisibility('GBP');
    // this.updateWidget();
  }
};

// create the widget
$.widget("doctool.statusWidget", StatusWidget);

$('.doctool_controls').statusWidget({});





