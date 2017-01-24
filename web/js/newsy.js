$(document).ready(function () {
    $("div.news-item fieldset.rating input[type='radio']").click(function (e) {
        $.post('index.php', {
            rating: $(this).attr('value'),
            url: $(this).siblings("input[type='hidden']").first().attr('value')
        });
    });
});