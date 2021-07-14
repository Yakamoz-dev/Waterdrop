require(
    [
        "jquery",
        "Magento_Ui/js/modal/modal",
        "mage/calendar"
    ],
    function(
        $,
        modal
    ) {
        var options = {
            type: 'popup',
            responsive: true,
            innerScroll: true,
            title: 'GENERAL WARRANTY TERMS AND CONDITIONS',
            modalClass: 'custom-modal',
            buttons: [{
                text: $.mage.__('Close'),
                class: '',
                click: function () {
                    this.closeModal();
                }
            }]
        };

        if ($('#popup-modal').length != 0) {
            var popup = modal(options, $('#popup-modal'));
            $("#click-me").on('click',function(){
                $("#popup-modal").modal('openModal');
            });
        }

        $(".datepicker").datepicker({
            dateFormat:'mm/dd/yy',
            changeMonth: true,
            changeYear: true,
            yearRange: '2018:2030',
            showMonthAfterYear: false,
        });

        $('#input-icon2').focus(function () {
            $(this).parent().find('.display-img').addClass('display-img-show')
        });
        $('#input-icon2').blur(function () {
            $(this).parent().find('.display-img').removeClass('display-img-show')
        });

        $('#line').change(function () {
            if ($(this).val() === "Online") {
                $('#platform_s').html('Website');
                $('#platform').html(
                    '<option value="" disabled selected><?php echo __("-- Please Select --") ?></option>' +
                    '<option value="Amazon">Amazon</option>' +
                    '<option value="Official Site">Official Site</option>'
                );
            } else if ($(this).val() === "Offline") {
                $('#platform_s').html('Store');
                $('#platform').html(
                    '<option value="" disabled selected><?php echo __("-- Please Select --") ?></option>' +
                    '<option value="Walmart">Walmart</option>' +
                    '<option value="Bestbuy">Bestbuy</option>' +
                    '<option value="Other">Other</option>'
                );
            }
        })
    });
