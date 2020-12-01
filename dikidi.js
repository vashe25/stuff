/**
 * dikidi.ru
 * copy clients from one project to another
 * or dump it to clients.json
 */

var jsonData = [];
var list = $('input[name="checkedId[]"]');
var customers = [];
for (let i = 0; i < list.length; i++) {
    customers.push(list[i].value);
}

function parse(html) {
    let d = $(html);
    return {
        name: d.find('input[name="name"]').val(),
        surname: d.find('input[name="surnamename"]').val(),
        number: d.find('input[name="number"]').val(),
        email: d.find('input[name="email"]').val(),
        comment: d.find('textarea[name="comment"]').val(),
    };
}

errors = [];
function saveClient(i) {
    $.ajax({
        url: 'https://dikidi.ru/ru/owner/ajax/clients/save/?company=230589',
        method: 'post',
        dataType: 'json',
        data: jsonData[i],
        success: function (res) {
            if (res.error) {
                errors.push({
                    message: res.message,
                    user: jsonData[i]
                });
            }
            i++;
            if (i == jsonData.length) {
                return;
            } else {
                saveClient(i)
            }
        },
    });
}

function getClient(i) {
    $.ajax({
        url: 'https://dikidi.ru/ru/owner/ajax/clients/settings/' + customers[i] + '/?company=326605',
        method: 'get',
        dataType: 'json',
        success: function (res) {
            jsonData.push(parse(res.html));
            i++;
            if (i == customers.length) {
                saveClient(0);
                return;
                var json = JSON.stringify(jsonData);
                var file = new Blob([json], {type: 'application/json'});
                var a = document.createElement('a');
                a.href = URL.createObjectURL(file);
                a.download = 'clients.json';
                a.click();
            } else {
                getClient(i);
            }
        },
    });
}

getClient(0);

console.log(errors);