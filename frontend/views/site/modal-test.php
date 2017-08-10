<?php
/**
 * Created by PhpStorm.
 * User: andrei
 * Date: 04/08/2017
 * Time: 10:37
 */
?>


<div id="modal-container">
    <div class="modal-background">
        <div class="modal">
            <div id="modal-contents">
                <h2>I'm a Modal</h2>
                <p>Hear me roar.</p>
            </div>
            <svg class="modal-svg" xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" preserveAspectRatio="none">
                <rect x="0" y="0" fill="none" width="226" height="162" rx="3" ry="3"></rect>
            </svg>
        </div>
    </div>
</div>
<div class="content">
    <h1>Modal Animations</h1>
    <div class="buttons">
        <div id="one" class="button">Unfolding</div>
        <div id="two" class="button">Revealing</div>
        <div id="three" class="button">Uncovering</div>
        <div id="four" class="button">Blow Up</div><br>
        <div id="five" class="button">Meep Meep</div>
        <div id="six" class="button">Sketch</div>
        <div id="seven" class="button">Bond</div>
    </div>
</div>

<script>
    $('.button').click(function(){
        var buttonId = $(this).attr('id');
        $('#modal-container').removeAttr('class').addClass(buttonId);
        $("#modal-contents").html("");
        $.ajax({
            url:'<?= Url::to(['/edit-post', 'id' => 44]) ?>',
            type: 'GET',
            success: function(response) {
    var result = obj = JSON.parse(response);
    console.log(result);
    $("#modal-contents").append(response);
},
            error: function (request, status, error) {
    window.alert(error);
}
        });
        $('body').addClass('modal-active');
    });

    $('#modal-container').click(function(){
        $(this).addClass('out');
        $('body').removeClass('modal-active');
    });
</script>