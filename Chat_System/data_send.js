$(function(){
    $('#submit').on('click',function(){
        $.ajax({
            url:'send_data.php',
            type:'post',
            data:{
                comment:$('#comment').val()
            }
        }).done(function(){
            document.getElementById('comment').value = '';
        })
    })
})