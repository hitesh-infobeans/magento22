define(['jquery', 'uiComponent', 'ko','Magento_Ui/js/modal/modal'], function ($, Component, ko,modal) {
        'use strict';
        return Component.extend({
            defaults: {
                template: 'Infobeans_CallForPrice/form'
            },
            initialize: function () {
                this.productId = ko.observable('');
                this.postUrl = ko.observable(window.postUrl);             
                this._super();
            },
                 
                
            showPopup: function (productid) {
               
                  var options = {
                type: 'popup',
                responsive: true,
                innerScroll: true,
                title: 'Call For Price',
                width:400,
                buttons: [{
                    text: $.mage.__('Submit'),
                    class: '',
                    click: function () {
                       alert($('#frmcallforprice').attr('action')); 
                        
                       if ($('#frmcallforprice').validation() &&
                            $('#frmcallforprice').validation('isValid')
                        ) {
                            $('#frmcallforprice').submit();
}
                    }
                }]
            };
            this.productId(productid);
            var popup = modal(options, $('#popup-modal'));

            $('#popup-modal').modal('openModal');       
               
            }
            
            
            
            
        });
    }
);
