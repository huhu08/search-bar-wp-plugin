jQuery(document).ready(function ($) {
    $('#my-api-search-button').on('click', function () {
        var searchQuery = $('#my-api-search-input').val();

        $.ajax({
            url: myApiSearchPlugin.ajax_url,
            type: 'POST',
            data: {
                action: 'my_api_search',
                search_query: searchQuery
            },
            success: function (response) {
                if (response.success) {
                    var results = response.data;
                    var resultsHtml = '';

                    results.forEach(function (result) {
                        resultsHtml += '<div class="result-item">';
                        resultsHtml += '<h3>' + result.title + '</h3>';
                        resultsHtml += '<p>' + result.description + '</p>';
                        resultsHtml += '</div>';
                    });

                    $('#my-api-search-results').html(resultsHtml);
                } else {
                    $('#my-api-search-results').html('<p>Error: ' + response.data + '</p>');
                }
            }
        });
    });
});
