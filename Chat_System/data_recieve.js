function update()
{
    $.ajax({
        url:'recieve_data.php',
        type:'get',
        datatype:'html'
    }).done(function(datas){
        let chat = datas;
        $('.talk').append(chat);
    })
}
$(function(){
    setInterval('update()', 1000);
})