require(
    [
        "jquery"
    ],
    function(
        $
    ) {
        $(".enter-icon").focus(function(){
            $(".img-div").show();
        });
        $(".enter-icon").blur(function(){
            $(".img-div").hide();
        });

        $("#check").click(function(){
            var id = $("#userInput").val();
            var url = $("#complete").val();
            $.ajax({
                type:"POST",
                cache:false,
                url:url,
                data: {"product_id":id},
                beforeSend:function(XHR) {
                    $('.input-loading').text('loading...');
                },
                success:function(result) {
                    var data = JSON.parse(result);
                    console.log(data);
                    if (data.success === 1) {
                        console.log($("#gift").val());
                        window.location.replace($("#gift").val());
                    } else {
                        $('.input-loading').html(data.info);
                    }
                }
            });
        });
    });