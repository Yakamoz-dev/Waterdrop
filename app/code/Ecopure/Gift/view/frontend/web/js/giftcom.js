require(
    [
        "jquery",
        "Magento_Ui/js/modal/modal"
    ],
    function(
        $,
        modal
    ) {
        var options = {
            type: 'popup',
            responsive: true,
            innerScroll: true,
            title: '',
            modalClass: 'custom-modal',
            buttons: [{
                text: $.mage.__('Close'),
                class: 'exit-amazon',
                click: function () {
                    this.closeModal();
                }
            }]
        };

        var options2 = {
            type: 'popup',
            responsive: true,
            innerScroll: true,
            title: '',
            modalClass: 'custom-modal2',
            buttons: [{
                text: $.mage.__('Close'),
                class: 'exit-modal',
                click: function () {
                    this.closeModal();
                    window.location.replace($('#gotourl').val());
                }
            }]
        };

        var options3 = {
            type: 'popup',
            responsive: true,
            innerScroll: true,
            title: '',
            modalClass: 'custom-modal3',
            buttons: [{
                text: $.mage.__('Close'),
                class: 'exit-modal',
                click: function () {
                    this.closeModal();
                    window.location.replace($('#gotourl').val());
                }
            }]
        };

        var popup = modal(options, $('#popup-modal'));
        var popup2 = modal(options2, $('#popup-modal2'));
        var popup3 = modal(options3, $('#popup-modal3'));

        $("input.checked").click(function () {
            $(this).next().css("color", "gold");
            for (i = 0; i < $(this).nextAll('label').length; i++) {
                $($(this).nextAll('label')[i]).css("color", "gold")
            }
            for (i = 0; i < $(this).prevAll('label').length; i++) {
                $($(this).prevAll('label')[i]).css("color", "#ddd")
            }
        });

        function IsPC() {
            var userAgentInfo = navigator.userAgent;
            var Agents = ["Android", "iPhone", "SymbianOS", "Windows Phone", "iPad", "iPod"];
            var flag = true;
            for (var v = 0; v < Agents.length; v++) {
                if (userAgentInfo.indexOf(Agents[v]) > 0) {
                    flag = false;
                    break;
                }
            }
            return flag;
        }

        function check(url,customer_id) {
            var ch = $('#channel').val();
            var star = $('input:radio:checked').val();
            var comment = $('#comment').val();
            var device;
            var submit_flag = true;
            if (IsPC()) {
                device = "PC";
            } else {
                device  = "Mobile";
            }
            if (!star) {
                $('.rating').after('<span class="error">Please give a rating</span>');
                submit_flag = false;
            }
            if (!ch) {
                $('#channel').after('<span class="error">Please choose a channel</span>');
                submit_flag = false;
            }
            if (comment.length <= 9) {
                $('#comment').after('<span class="error">Comment must be at least 10 characters long</span>');
                submit_flag = false;
            }
            if (submit_flag) {
                $('.error').text('');
                $.ajax({
                    type:"POST",
                    cache:false,
                    url:url,
                    data: {"customer_id":customer_id,"comment":comment,"star":star,"device":device,"channel":ch},
                    success:function(data) {}
                });

                if (ch === 'amazon' && star === "5") {
                    $("#popup-modal").modal('openModal');
                    $('#amazoncomment-box').val(comment);
                } else if (ch === 'amazon') {
                    $("#popup-modal2").modal('openModal');
                } else {
                    $("#popup-modal3").modal('openModal');
                }
            }
        }

        function sendAmazon(asin) {
            copyComment();
            var url = 'https://www.amazon.com/review/create-review?&asin=' + asin;
            window.open(url, '_blank');
            window.location.replace($('#gotourl').val());
        }

        function copyComment(){
            var copyText = document.getElementById("amazoncomment-box");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            document.execCommand("copy");
        }

        window.IsPC = IsPC;
        window.check = check;
        window.sendAmazon = sendAmazon;
    });