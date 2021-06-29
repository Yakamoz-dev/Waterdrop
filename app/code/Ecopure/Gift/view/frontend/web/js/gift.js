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

        var popup = modal(options, $('#popup-modal'));
        $("#click-me").on('click',function(){
            $("#popup-modal").modal('openModal');
        });

        $(".datepicker").datepicker({
            dateFormat:'mm/dd/yy',
            changeMonth: true,
            changeYear: true,
            yearRange: '2010:2025',
            showMonthAfterYear: false,
        });
        $('#input-icon1').focus(function () {
            $(this).parent().find('.display-img').addClass('display-img-show')
        });
        $('#input-icon1').blur(function () {
            $(this).parent().find('.display-img').removeClass('display-img-show')
        });
        $('#input-icon2').focus(function () {
            $(this).parent().find('.display-img').addClass('display-img-show')
        });
        $('#input-icon2').blur(function () {
            $(this).parent().find('.display-img').removeClass('display-img-show')
        });

        $('#change-select').change(function () {
            var isOrderNo = $(this).find('option:selected').data('order');
            verifyByOrder(isOrderNo);
        })
        function verifyByOrder(isOrderNo) {
            if (isOrderNo) {
                $('#Serial-Number').removeClass("display-img-show");
                $('.part1-rightInput-div').css("margin-right","2%");
                $('.part1-leftInput-div').css("margin-right","0");
                $('#ro_productid').removeClass('required');
            } else {
                $('#Serial-Number').addClass("display-img-show");
                $('.part1-rightInput-div').css({ "margin-right":0});
                $('.part1-leftInput-div').css("margin-right","2%");
                $('#ro_productid').addClass('required');
            }
        }
    });