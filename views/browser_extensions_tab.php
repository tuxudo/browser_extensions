<div id="browser_extensions-tab"></div>
<h2 data-i18n="browser_extensions.browser_extensions"></h2>

<div id="browser_extensions-msg" data-i18n="listing.loading" class="col-lg-12 text-center"></div>

<script>
$(document).on('appReady', function(){
	$.getJSON(appUrl + '/module/browser_extensions/get_data/' + serialNumber, function(data){

        // Check if we have data
        if(!data[0]){
            $('#browser_extensions-msg').text(i18n.t('no_data'));
            $('#browser_extensions-header').removeClass('hide');

            // Update the tab browser_extensions count
            $('#browser_extensions-cnt').text("0");

        } else {

            // Hide loading message
            $('#browser_extensions-msg').text('');
            $('#browser_extensions-view').removeClass('hide');

            // Set count of extensions
            $('#browser_extensions-cnt').text(data.length);

            $.each(data, function(i,d){
                // Generate rows from data
                var rows = ''

                for (var prop in d){

                    // Blank empty rows
                    if(d[prop] !== 0 && d[prop] == '' || d[prop] == null || prop == 'name'){
                        rows = rows
                    
                    } else if((prop == 'enabled') && d[prop] == 1){
                        rows = rows + '<tr><th>'+i18n.t('browser_extensions.'+prop)+'</th><td>'+i18n.t('yes')+'</td></tr>';
                    } else if((prop == 'enabled') && d[prop] == 0){
                        rows = rows + '<tr><th>'+i18n.t('browser_extensions.'+prop)+'</th><td>'+i18n.t('no')+'</td></tr>';

                    } else if((prop == "date_installed" && d[prop] > 100)){
                        var date = new Date(d[prop] * 1000);
                        rows = rows + '<tr><th>'+i18n.t('browser_extensions.'+prop)+'</th><td><span title="'+moment(date).fromNow()+'">'+moment(date).format('llll')+'</span></td></tr>';

                    } else {
                        rows = rows + '<tr><th>'+i18n.t('browser_extensions.'+prop)+'</th><td>'+d[prop]+'</td></tr>';
                    }
                }

                $('#browser_extensions-tab')

                // Show correct browser icon for extension
                if (d.browser == "Google Chrome"){
                    $('#browser_extensions-tab')
                    .append($('<h4>')
                         .append($('<i>')
                             .addClass('fa fa-chrome'))
                         .append(' '+d.name))
                    .append($('<div style="max-width:650px;">')
                         .append($('<table>')
                             .addClass('table table-striped table-condensed')
                             .append($('<tbody>')
                                 .append(rows))))

                } else if (d.browser == "Firefox"){
                    $('#browser_extensions-tab')
                    .append($('<h4>')
                         .append($('<i>')
                             .addClass('fa fa-firefox'))
                         .append(' '+d.name))
                    .append($('<div style="max-width:650px;">')
                         .append($('<table>')
                             .addClass('table table-striped table-condensed')
                             .append($('<tbody>')
                                 .append(rows))))

                } else if (d.browser == "Microsoft Edge"){
                    $('#browser_extensions-tab')
                    .append($('<h4>')
                         .append($('<i>')
                             .addClass('fa fa-internet-explorer'))
                         .append(' '+d.name))
                    .append($('<div style="max-width:650px;">')
                         .append($('<table>')
                             .addClass('table table-striped table-condensed')
                             .append($('<tbody>')
                                 .append(rows))))
                }
            })
        }
	});
});
</script>
