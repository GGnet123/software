var productsList = [];
$('.productsSelect').change(function () {
    var selectedProduct = $(this).children('option:selected').text();

    var selectedProductId = $(this).children('option:selected').val();

    if (selectedProductId!="") {
        productsList.push(selectedProductId);
    }
    if (selectedProduct!='Products'){
        $('.selected-products').append('<div id="'+selectedProductId+'">'+selectedProduct+'<i class="glyphicon glyphicon-remove" onclick="removeProduct('+selectedProductId+')"></i>/');
    }
    console.log(productsList);
});

function removeProduct(id) {
    $('#'+id).remove();
    productsList = $.grep(productsList,function (value) {
        return value != id;
    });
}

$('#submitBtn').on('click',function () {
    var name = $('#name').val();
    var address = $('#address').val();
    var card = $('#card').val();
    var delivery = $('#delivery').val();
    var phone = $('#phone').val();
    var taken = $('#taken').val();

    console.log(productsList);
        $.post(
            '/orders/save',
            {'name':name,'address':address,'card':card,'delivery':delivery,'phone':phone,'taken':taken,'products_ids' : productsList}
        );
        return false;
});