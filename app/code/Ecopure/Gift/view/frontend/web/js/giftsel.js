require(
    [
        "jquery",
        "Magento_Ui/js/modal/modal",
        "mage/mage"
    ],
    function(
        $,
        modal
    ) {
        var dataForm = $("#gift-form");
        dataForm.mage('validation', {ignore: ''});

        var options = {
            type: 'popup',
            responsive: true,
            innerScroll: true,
            title: 'CHOOSE FRIDGE MODELS',
            modalClass: 'custom-modal',
            buttons: [{
                text: $.mage.__('Close'),
                class: '',
                click: function () {
                    this.closeModal();
                }
            }]
        };

        var options2 = {
            type: 'popup',
            responsive: true,
            innerScroll: true,
            title: 'GENERAL WARRANTY TERMS AND CONDITIONS',
            modalClass: 'custom-modal2',
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

        var popup2 = modal(options2, $('#popup-modal2'));
        $("#click-me2").on('click',function(){
            $("#popup-modal2").modal('openModal');
        });

        var country=$("#country");
        var stateSelect=$("#state");
        var stateInput=$(".state-input");
        country.change(function(){
            if(country.val()==="us" || country.val()==="ca"){
                stateSelect.show();
                stateInput.hide();
            }else{
                stateSelect.hide();
                stateInput.show();
            }
        });

        var button = $(".select-product-button");
        button.click(function () {
            $(this).addClass("select-button-click");
            $(this).parent().siblings().children("button").removeClass("select-button-click");
            if($(this).siblings(".select-product-title").data("productname")==="Warranty"){
                $(".disabled").prop("disabled",true);
                $("#disabled-span").show();
                $("#productids").removeClass('required');
                $("#first_name").removeClass('required');
                $("#last_name").removeClass('required');
                $("#address1").removeClass('required');
                $("#city").removeClass('required');
                $("#country").removeClass('required');
                $("#state").removeClass('required');
                $("#zip").removeClass('required');
                $("#telephone").removeClass('required');
                $("#conditions").removeClass('required');
            }else{
                $(".disabled").prop("disabled",false);
                $("#disabled-span").hide();
                $("#productids").addClass('required');
                $("#first_name").addClass('required');
                $("#last_name").addClass('required');
                $("#address1").addClass('required');
                $("#city").addClass('required');
                $("#country").addClass('required');
                $("#state").addClass('required');
                $("#zip").addClass('required');
                $("#telephone").addClass('required');
                $("#conditions").addClass('required');
            }
        });

        $(".custom-modal button:not(.choose)").click(() => {
            $(".select-product-button").removeClass("select-button-click");
        });

        function showNotes() {
            $(".pre-notes").fadeOut();
        }

        function cancel(pid) {
            $(".select-product-button").removeClass("select-button-click");
            $("#brands").val('');
            $("#models").val('');
            $("#product_name").val('');
            $("#productids").val('');
            $("#product-name-"+pid+" span").html('');
        }

        function chooseproductmodal(model,brand,pid,name = null){
            $("#brands").val($("#"+brand).val());
            $("#models").val($("#"+model).val());
            $("#productids").val(pid);
            $("#product_name").val(name);
            var txt = " ";
            if(brand !== ''){
                txt += " For Brand : "+$("#"+brand).val();
            }
            if(model !== ''){
                txt += " Model : "+$("#"+model).val();
            }
            txt += " ";
            $("#product-name-"+pid+" span").html(txt);
        }

        window.showNotes = showNotes;
        window.cancel = cancel;
        window.chooseproductmodal = chooseproductmodal;
    });