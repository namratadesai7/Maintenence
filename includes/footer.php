<script>
    $(document).ready(function() {
        var id=$('#empid').val();
      
        if(id<3000){
            $('.hide').hide();
        }
    });




$(document).ready(function() {
    //jquery for toggle sub menus
    $('.sub-btn').click(function() {
        $(this).next('.sub-menu').toggleClass('showMenu');
        $(this).find('.dropdown').toggleClass('rotate');
    });
    // Calculate Amount
    $('.qty,.rate').on("change", function() {
        var x = $('.qty').val();
        var y = $('.rate').val();
        $('.total').val(x * y);
    });


    $('.bonus,.hra,.wa,.ot').on("focusout", function() {
        var amt = parseFloat($('.total').val());
        var bonus = parseFloat($('.bonus').val());
        var hra = parseFloat($('.hra').val());
        var ot = parseFloat($('.ot').val());
        var wa = parseFloat($('.wa').val());

        var totalAmount = amt + hra + ot + wa + bonus;
        // Calculate Total Amount
        $('.ta').val(totalAmount);
        var tA = parseFloat($('.ta').val());

        // ESIC Calculation
        var esic = ((totalAmount * 3.25) / 100).toFixed(2);
        $('.esic').val(esic);
        var esicAmt = parseFloat($('.esic').val());

        // PF Calculation
        var pf = ((totalAmount * 13) / 100).toFixed(2);
        $('.pf').val(pf);
        var pfAmt = parseFloat($('.pf').val());

        // Service Charges  Calculation
        var sc = ((totalAmount * 4) / 100).toFixed(2);
        $('.service').val(sc);
        var scAmt = parseFloat($('.service').val());

        // Sub Total Calculation
        var st = tA + esicAmt + pfAmt + scAmt;
        $('.subtotal').val((st).toFixed(2));
        var subTtl = parseFloat($('.subtotal').val());

        // SGST Calculation
        var sgst = ((subTtl * 9) / 100).toFixed(2);
        var cgst = ((subTtl * 9) / 100).toFixed(2);
        $('.sgst').val(sgst);
        $('.cgst').val(cgst);
        var sgstAmt = parseFloat($('.sgst').val());
        var cgstAmt = parseFloat($('.cgst').val());

        // Invoice Total Calculation
        var invoice = subTtl + sgstAmt + cgstAmt;
        $('.invoice').val((invoice).toFixed(2));

        var word = invoice;
        $('.word').val((word).toFixed(2))
    });

    // Print the Bill
    $('.print').on('click', function() {
        window.open('bill_pdf.php');
    });

    // highlight link on click

    $('.menu-link').click(function() {
        $('.menu-link').removeClass('active');
        $('.sub-btn').removeClass('sub-active');
        $('.menu-link').removeClass('sub-active');
        $('.sub-item').removeClass('active');
        $('.sub-menu').removeClass('showMenu');
        $(this).addClass('active');
    });
    $('.sub-item').click(function() {
        $('.sub-item').removeClass('active')
        $('.drop-menu').removeClass('sub-active');
        $('.sub-menu').removeClass('showMenu');
        $(this).parent().addClass('showMenu');

        $(this).addClass('active');
        $(this).parent().siblings().addClass('sub-active');
        $('.menu-link').removeClass('active');
    });
    var path = window.location.pathname.split("/").pop();
    if (path == '') {
        path = 'index.php';
    } else {
        // Extract the file name from the path
        path = path.split('/').pop();
    }

    var target = $('.sidebar-menu ul li a[href="' + path + '"]');
    target.addClass('active');

    var sublink = $('.sidebar-menu ul li .sub-menu .sub-item a[href="' + path + '"]');
    sublink.addClass('active');


    if (target.hasClass('active')) {
        if (target.hasClass('sub-item')) {
            target.parent().addClass('showMenu');
            if (target.parent().hasClass('sub-menu')) {
                target.parent().siblings().addClass('sub-active');
            }
        }
    }
});
</script>
</main>


</div>
</div>
</body>

</html>